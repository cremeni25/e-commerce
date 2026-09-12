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
  products: {
    name: string;
    sku: string;
  } | null;
};

type ShippingReadiness = {
  ready: boolean;
  items: number;
  missing_inventory: number;
  missing_dimensions: number;
  missing_origin: number;
  reason: string;
};

type ShippingQuote = {
  quote_id: string;
  provider: string;
  service_name: string;
  amount_cents: number;
  estimated_days: number | null;
  expires_at: string;
};

const emptyForm = {
  recipient_name: '',
  postal_code: '',
  street: '',
  number: '',
  complement: '',
  neighborhood: '',
  city: '',
  state: '',
};

function money(cents: number) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(cents / 100);
}

function readinessMessage(readiness: ShippingReadiness | null) {
  if (!readiness) return 'Verificando dados logísticos…';
  if (readiness.reason === 'cart_empty' || readiness.reason === 'cart_not_found') return 'O carrinho está vazio.';
  if (readiness.missing_inventory > 0) return 'Estoque real ainda não foi validado para todos os itens.';
  if (readiness.missing_dimensions > 0) return 'Peso e dimensões reais ainda não foram validados para todos os itens.';
  if (readiness.missing_origin > 0) return 'A origem logística do fornecedor ainda não foi configurada para todos os itens.';
  return readiness.ready ? 'Dados logísticos validados. A cotação pode ser consultada.' : 'Frete ainda não está liberado.';
}

export default function CheckoutPage() {
  const [userId, setUserId] = useState<string | null>(null);
  const [userEmail, setUserEmail] = useState<string | null>(null);
  const [addresses, setAddresses] = useState<Address[]>([]);
  const [selectedAddressId, setSelectedAddressId] = useState<string | null>(null);
  const [items, setItems] = useState<CartItem[]>([]);
  const [readiness, setReadiness] = useState<ShippingReadiness | null>(null);
  const [quote, setQuote] = useState<ShippingQuote | null>(null);
  const [form, setForm] = useState(emptyForm);
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [quoting, setQuoting] = useState(false);
  const [creatingOrder, setCreatingOrder] = useState(false);
  const [orderId, setOrderId] = useState<string | null>(null);

  useEffect(() => {
    void loadCheckout();
  }, []);

  async function loadCheckout() {
    setLoading(true);
    setQuote(null);
    const { data: auth } = await supabase.auth.getUser();
    const user = auth.user;
    setUserId(user?.id || null);
    setUserEmail(user?.email || null);

    if (!user) {
      setLoading(false);
      return;
    }

    const [{ data: addressData }, { data: carts }, { data: readinessData }] = await Promise.all([
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
      supabase.rpc('checkout_shipping_readiness'),
    ]);

    const normalizedAddresses = (addressData || []) as Address[];
    setAddresses(normalizedAddresses);
    setSelectedAddressId((current) => current && normalizedAddresses.some((a) => a.id === current) ? current : normalizedAddresses[0]?.id || null);
    setReadiness((readinessData || null) as ShippingReadiness | null);

    const cartId = carts?.[0]?.id;
    if (cartId) {
      const { data: cartItems } = await supabase
        .from('cart_items')
        .select('quantity,unit_price_cents,products(name,sku)')
        .eq('cart_id', cartId);
      setItems((cartItems || []) as unknown as CartItem[]);
    } else {
      setItems([]);
    }

    setLoading(false);
  }

  const subtotal = useMemo(
    () => items.reduce((sum, item) => sum + item.quantity * item.unit_price_cents, 0),
    [items]
  );

  const total = subtotal + (quote?.amount_cents || 0);
  const selectedAddress = addresses.find((address) => address.id === selectedAddressId) || null;

  function updateField(field: keyof typeof emptyForm, value: string) {
    setForm((current) => ({ ...current, [field]: value }));
  }

  async function lookupPostalCode() {
    const postalCode = form.postal_code.replace(/\D/g, '');
    if (postalCode.length !== 8) {
      setMessage('Informe um CEP válido com 8 dígitos.');
      return;
    }

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
        neighborhood: data.bairro || current.neighborhood,
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
    const { error } = await supabase.rpc('save_default_address', {
      p_recipient_name: form.recipient_name,
      p_postal_code: postalCode,
      p_street: form.street,
      p_number: form.number,
      p_complement: form.complement,
      p_neighborhood: form.neighborhood,
      p_city: form.city,
      p_state: state,
    });

    if (error) {
      setMessage(error.message.includes('invalid_postal_code') ? 'Informe um CEP válido com 8 dígitos.' : 'Não foi possível salvar o endereço.');
      setSaving(false);
      return;
    }

    setForm(emptyForm);
    setMessage('Endereço salvo com segurança.');
    setSaving(false);
    await loadCheckout();
  }

  async function calculateShipping() {
    if (!selectedAddressId || !readiness?.ready) return;
    setQuoting(true);
    setMessage('Consultando frete real…');
    setQuote(null);

    const { data, error } = await supabase.functions.invoke('shipping-quote', {
      body: { address_id: selectedAddressId },
    });

    if (error || !data) {
      const context = (error as { context?: Response } | null)?.context;
      let code = '';
      if (context) {
        try {
          const payload = await context.clone().json();
          code = payload?.error || '';
        } catch {
          code = '';
        }
      }
      if (code === 'logistics_provider_not_configured') {
        setMessage('A integração logística está pronta, mas o token de homologação do Melhor Envio ainda não foi conectado.');
      } else {
        setMessage('A cotação real não pôde ser concluída. Nenhum valor fictício foi aplicado.');
      }
      setQuoting(false);
      return;
    }

    setQuote(data as ShippingQuote);
    setMessage('Frete cotado em tempo real.');
    setQuoting(false);
  }

  async function createOrder() {
    if (!quote || !selectedAddress || !userEmail) return;
    setCreatingOrder(true);
    setMessage('Revalidando preço, estoque e frete…');

    const { data, error } = await supabase.rpc('create_checkout_order', {
      p_customer_name: selectedAddress.recipient_name,
      p_customer_email: userEmail,
      p_address_id: selectedAddress.id,
      p_shipping_quote_id: quote.quote_id,
    });

    if (error || !data) {
      setMessage('O pedido não foi criado porque uma validação de preço, estoque ou frete falhou.');
      setCreatingOrder(false);
      return;
    }

    setOrderId(String(data));
    setMessage('Pedido criado com pagamento pendente. Nenhum gateway foi acionado.');
    setCreatingOrder(false);
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
        <p>Endereço, origem do fornecedor, preço, estoque e frete são validados antes do pedido.</p>
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
                    onChange={() => {
                      setSelectedAddressId(address.id);
                      setQuote(null);
                    }}
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
            <h3>{addresses.length ? 'Cadastrar novo endereço principal' : 'Cadastrar endereço'}</h3>
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
            <label>Bairro<input required value={form.neighborhood} onChange={(e) => updateField('neighborhood', e.target.value)} /></label>
            <label>Cidade<input required value={form.city} onChange={(e) => updateField('city', e.target.value)} /></label>
            <label>UF<input required maxLength={2} value={form.state} onChange={(e) => updateField('state', e.target.value.toUpperCase())} /></label>
            <button className="secondary" type="submit" disabled={saving}>{saving ? 'Salvando…' : 'Salvar endereço'}</button>
          </form>
        </section>

        <aside className="checkoutCard checkoutSummary">
          <h2>Resumo</h2>
          <div><span>Subtotal</span><strong>{money(subtotal)}</strong></div>
          <div><span>Frete</span><strong>{quote ? money(quote.amount_cents) : 'A calcular'}</strong></div>
          <div><span>Total</span><strong>{quote ? money(total) : 'A confirmar'}</strong></div>

          <p className="checkoutNotice">{readinessMessage(readiness)}</p>

          {quote && (
            <p className="checkoutNotice">
              <strong>{quote.service_name}</strong><br />
              {quote.estimated_days != null ? `Prazo estimado: até ${quote.estimated_days} dia(s).` : 'Prazo informado pela transportadora no momento da cotação.'}
            </p>
          )}

          <button
            className="primary"
            type="button"
            disabled={!selectedAddressId || !readiness?.ready || quoting || Boolean(orderId)}
            onClick={calculateShipping}
          >
            {quoting ? 'Cotando…' : quote ? 'Recalcular frete' : 'Calcular frete real'}
          </button>

          <button
            className="primary"
            type="button"
            disabled={!quote || creatingOrder || Boolean(orderId)}
            onClick={createOrder}
          >
            {creatingOrder ? 'Validando…' : orderId ? 'Pedido criado' : 'Confirmar pedido sem pagamento'}
          </button>

          <small>Pagamento real permanece desabilitado. O pedido, quando liberado, nasce com payment_status = pending.</small>
          {orderId && <small>Pedido homologado: {orderId}</small>}
        </aside>
      </div>

      {message && <p className="accountMessage" role="status">{message}</p>}
    </main>
  );
}
