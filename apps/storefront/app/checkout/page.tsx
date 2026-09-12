'use client';

import Link from 'next/link';
import { FormEvent, useEffect, useMemo, useState } from 'react';
import { supabase } from '@/lib/supabase-browser';

type Address = {
  id: string;
  label: string;
  recipient_name: string;
  postal_code: string;
  street: string;
  number: string;
  complement: string | null;
  district: string;
  city: string;
  state: string;
  country: string;
  is_default: boolean;
};

type CartItem = {
  quantity: number;
  unit_price_cents: number;
};

const emptyForm = {
  recipient_name: '',
  postal_code: '',
  street: '',
  number: '',
  complement: '',
  district: '',
  city: '',
  state: '',
};

export default function CheckoutPage() {
  const [userId, setUserId] = useState<string | null>(null);
  const [userEmail, setUserEmail] = useState<string | null>(null);
  const [addresses, setAddresses] = useState<Address[]>([]);
  const [selectedAddressId, setSelectedAddressId] = useState<string | null>(null);
  const [items, setItems] = useState<CartItem[]>([]);
  const [form, setForm] = useState(emptyForm);
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    void loadCheckout();
  }, []);

  async function loadCheckout() {
    setLoading(true);
    const { data: auth } = await supabase.auth.getUser();
    const user = auth.user;
    setUserId(user?.id || null);
    setUserEmail(user?.email || null);

    if (!user) {
      setLoading(false);
      return;
    }

    const [{ data: addressData }, { data: carts }] = await Promise.all([
      supabase
        .from('customer_addresses')
        .select('id,label,recipient_name,postal_code,street,number,complement,district,city,state,country,is_default')
        .eq('customer_id', user.id)
        .order('is_default', { ascending: false })
        .order('created_at', { ascending: false }),
      supabase
        .from('carts')
        .select('id')
        .eq('customer_id', user.id)
        .eq('status', 'open')
        .order('created_at', { ascending: false })
        .limit(1),
    ]);

    const normalizedAddresses = (addressData || []) as Address[];
    setAddresses(normalizedAddresses);
    setSelectedAddressId(normalizedAddresses[0]?.id || null);

    const cartId = carts?.[0]?.id;
    if (cartId) {
      const { data: cartItems } = await supabase
        .from('cart_items')
        .select('quantity,unit_price_cents')
        .eq('cart_id', cartId);
      setItems((cartItems || []) as CartItem[]);
    } else {
      setItems([]);
    }

    setLoading(false);
  }

  const subtotal = useMemo(
    () => items.reduce((sum, item) => sum + item.quantity * item.unit_price_cents, 0),
    [items]
  );

  function updateField(field: keyof typeof emptyForm, value: string) {
    setForm((current) => ({ ...current, [field]: value }));
  }

  async function lookupPostalCode() {
    const postalCode = form.postal_code.replace(/\D/g, '');
    if (postalCode.length !== 8) return;

    setMessage('Consultando CEP…');
    try {
      const response = await fetch(`https://viacep.com.br/ws/${postalCode}/json/`);
      const data = await response.json();
      if (!response.ok || data.erro) {
        setMessage('CEP não encontrado. Preencha o endereço manualmente.');
        return;
      }
      setForm((current) => ({
        ...current,
        postal_code: postalCode,
        street: data.logradouro || current.street,
        district: data.bairro || current.district,
        city: data.localidade || current.city,
        state: (data.uf || current.state).toUpperCase(),
      }));
      setMessage('CEP localizado. Confira os dados e informe o número.');
    } catch {
      setMessage('Não foi possível consultar o CEP agora. Você pode preencher o endereço manualmente.');
    }
  }

  async function saveAddress(event: FormEvent) {
    event.preventDefault();
    if (!userId) return;

    const postalCode = form.postal_code.replace(/\D/g, '');
    const state = form.state.trim().toUpperCase();
    if (postalCode.length !== 8 || state.length !== 2) {
      setMessage('Confira CEP e UF antes de salvar o endereço.');
      return;
    }

    setSaving(true);
    setMessage('');
    const { error } = await supabase.from('customer_addresses').insert({
      customer_id: userId,
      label: addresses.length ? 'Entrega' : 'Principal',
      recipient_name: form.recipient_name.trim(),
      postal_code: postalCode,
      street: form.street.trim(),
      number: form.number.trim(),
      complement: form.complement.trim() || null,
      district: form.district.trim(),
      city: form.city.trim(),
      state,
      country: 'BR',
      is_default: addresses.length === 0,
    });

    if (error) {
      setMessage(`Não foi possível salvar o endereço: ${error.message}`);
      setSaving(false);
      return;
    }

    setForm(emptyForm);
    setMessage('Endereço salvo.');
    setSaving(false);
    await loadCheckout();
  }

  if (loading) {
    return <main className="checkoutPage shell"><p>Carregando checkout…</p></main>;
  }

  if (!userEmail) {
    return (
      <main className="checkoutPage shell">
        <div className="storeBack"><Link href="/carrinho">← Voltar ao carrinho</Link></div>
        <section className="accountCard">
          <h1>Checkout</h1>
          <p>Entre na sua conta antes de continuar.</p>
          <Link className="primary" href="/conta">Entrar</Link>
        </section>
      </main>
    );
  }

  return (
    <main className="checkoutPage shell">
      <div className="storeBack"><Link href="/carrinho">← Voltar ao carrinho</Link></div>
      <header className="storeHero">
        <span className="kicker">ENTREGA</span>
        <h1>Checkout</h1>
        <p>Primeiro confirmamos o endereço. Depois o frete será cotado por um provedor logístico real.</p>
      </header>

      <div className="checkoutGrid">
        <section className="checkoutCard">
          <h2>Endereço de entrega</h2>

          {addresses.length > 0 && (
            <div className="addressList">
              {addresses.map((address) => (
                <label className="addressOption" key={address.id}>
                  <input
                    type="radio"
                    name="delivery-address"
                    checked={selectedAddressId === address.id}
                    onChange={() => setSelectedAddressId(address.id)}
                  />
                  <span>
                    <strong>{address.recipient_name}</strong>
                    <small>{address.street}, {address.number}{address.complement ? ` · ${address.complement}` : ''}</small>
                    <small>{address.district} · {address.city}/{address.state} · CEP {address.postal_code}</small>
                  </span>
                </label>
              ))}
            </div>
          )}

          <form className="accountForm" onSubmit={saveAddress}>
            <h3>{addresses.length ? 'Adicionar outro endereço' : 'Cadastrar endereço'}</h3>
            <label>Nome do destinatário<input required value={form.recipient_name} onChange={(e) => updateField('recipient_name', e.target.value)} /></label>
            <label>CEP
              <div className="inlineField">
                <input required inputMode="numeric" maxLength={9} value={form.postal_code} onChange={(e) => updateField('postal_code', e.target.value)} />
                <button className="secondary" type="button" onClick={lookupPostalCode}>Buscar CEP</button>
              </div>
            </label>
            <label>Rua<input required value={form.street} onChange={(e) => updateField('street', e.target.value)} /></label>
            <label>Número<input required value={form.number} onChange={(e) => updateField('number', e.target.value)} /></label>
            <label>Complemento<input value={form.complement} onChange={(e) => updateField('complement', e.target.value)} /></label>
            <label>Bairro<input required value={form.district} onChange={(e) => updateField('district', e.target.value)} /></label>
            <label>Cidade<input required value={form.city} onChange={(e) => updateField('city', e.target.value)} /></label>
            <label>UF<input required maxLength={2} value={form.state} onChange={(e) => updateField('state', e.target.value.toUpperCase())} /></label>
            <button className="secondary" type="submit" disabled={saving}>{saving ? 'Salvando…' : 'Salvar endereço'}</button>
          </form>
        </section>

        <aside className="checkoutCard checkoutSummary">
          <h2>Resumo</h2>
          <div><span>Subtotal</span><strong>{new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(subtotal / 100)}</strong></div>
          <div><span>Frete</span><strong>A calcular</strong></div>
          <p className="checkoutNotice">Nenhum valor de frete fictício será aplicado. A criação do pedido fica bloqueada até recebermos uma cotação válida e não expirada de um provedor logístico.</p>
          <button className="primary" type="button" disabled>Continuar para pagamento</button>
          <small>Pagamento real permanece desabilitado nesta homologação.</small>
        </aside>
      </div>

      {message && <p className="accountMessage" role="status">{message}</p>}
    </main>
  );
}
