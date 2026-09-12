'use client';

import Link from 'next/link';
import { useEffect, useMemo, useState } from 'react';
import { supabase } from '@/lib/supabase-browser';

type CartItem = {
  id: string;
  quantity: number;
  unit_price_cents: number;
  product_id: string;
  products: { name: string; slug: string; sku: string } | null;
};

export default function CartPage() {
  const [items, setItems] = useState<CartItem[]>([]);
  const [userEmail, setUserEmail] = useState<string | null>(null);
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(true);
  const [name, setName] = useState('');

  async function loadCart() {
    setLoading(true);
    const { data: auth } = await supabase.auth.getUser();
    const user = auth.user;
    setUserEmail(user?.email || null);
    if (!user) { setItems([]); setLoading(false); return; }

    const { data: carts } = await supabase.from('carts').select('id').eq('customer_id', user.id).eq('status', 'open').order('created_at', { ascending: false }).limit(1);
    const cartId = carts?.[0]?.id;
    if (!cartId) { setItems([]); setLoading(false); return; }

    const { data, error } = await supabase.from('cart_items').select('id,quantity,unit_price_cents,product_id,products(name,slug,sku)').eq('cart_id', cartId).order('created_at');
    if (error) setMessage('Não foi possível carregar o carrinho.');
    setItems((data || []) as unknown as CartItem[]);
    setLoading(false);
  }

  useEffect(() => { loadCart(); }, []);

  const total = useMemo(() => items.reduce((sum, item) => sum + item.quantity * item.unit_price_cents, 0), [items]);

  async function changeQuantity(id: string, quantity: number) {
    const { error } = await supabase.rpc('set_cart_item_quantity', { p_item_id: id, p_quantity: quantity });
    if (error) setMessage('Quantidade indisponível para o estoque atual.');
    await loadCart();
  }

  async function removeItem(id: string) {
    await supabase.rpc('remove_cart_item', { p_item_id: id });
    await loadCart();
  }

  async function createOrder() {
    if (!userEmail || !items.length) return;
    setMessage('');
    const { data, error } = await supabase.rpc('create_order_from_cart', {
      p_customer_name: name,
      p_customer_email: userEmail,
      p_shipping_address: {},
      p_billing_address: {},
    });
    if (error) {
      setMessage(error.message.includes('insufficient_stock') ? 'Pedido bloqueado: disponibilidade do fornecedor ainda não confirmada.' : 'Não foi possível criar o pedido agora.');
      return;
    }
    setMessage(`Pedido ${String(data).slice(0, 8)} criado com pagamento pendente.`);
    await loadCart();
  }

  return (
    <main className="cartPage shell">
      <div className="storeBack"><Link href="/loja">← Continuar comprando</Link></div>
      <header className="storeHero"><span className="kicker">CREMENI</span><h1>Carrinho</h1><p>Preço e disponibilidade são revalidados antes da criação do pedido.</p></header>
      {!userEmail && !loading && <section className="accountCard"><p>Entre na sua conta para usar o carrinho persistente.</p><Link className="primary" href="/conta">Entrar</Link></section>}
      {userEmail && <section className="cartCard">
        {loading ? <p>Carregando…</p> : items.length === 0 ? <p>Seu carrinho está vazio.</p> : items.map((item) => (
          <article className="cartLine" key={item.id}>
            <div><strong>{item.products?.name || 'Produto CREMENI'}</strong><small>{item.products?.sku}</small></div>
            <div className="cartQty"><button onClick={() => item.quantity > 1 && changeQuantity(item.id, item.quantity - 1)}>−</button><span>{item.quantity}</span><button onClick={() => changeQuantity(item.id, item.quantity + 1)}>+</button></div>
            <strong>{new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format((item.quantity * item.unit_price_cents) / 100)}</strong>
            <button className="textButton" onClick={() => removeItem(item.id)}>Remover</button>
          </article>
        ))}
        {items.length > 0 && <div className="cartSummary"><div><span>Total parcial</span><strong>{new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(total / 100)}</strong></div><label>Nome para o pedido<input value={name} onChange={(e) => setName(e.target.value)} /></label><button className="primary" onClick={createOrder}>Criar pedido</button><small>Pagamento permanece desabilitado nesta homologação.</small></div>}
      </section>}
      {message && <p className="accountMessage">{message}</p>}
    </main>
  );
}
