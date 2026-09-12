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

type ShippingQuote = {
  amount_cents: number;
  service_name: string;
  estimated_days: number | null;
  expires_at: string;
};

function money(cents: number) {
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(cents / 100);
}

export default function CartPage() {
  const [items, setItems] = useState<CartItem[]>([]);
  const [quote, setQuote] = useState<ShippingQuote | null>(null);
  const [userEmail, setUserEmail] = useState<string | null>(null);
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(true);

  async function loadCart() {
    setLoading(true);
    setQuote(null);
    const { data: auth } = await supabase.auth.getUser();
    const user = auth.user;
    setUserEmail(user?.email || null);
    if (!user) { setItems([]); setLoading(false); return; }

    const { data: carts } = await supabase
      .from('carts')
      .select('id')
      .eq('customer_id', user.id)
      .eq('status', 'open')
      .order('created_at', { ascending: false })
      .limit(1);

    const cartId = carts?.[0]?.id;
    if (!cartId) { setItems([]); setLoading(false); return; }

    const [{ data, error }, { data: quoteData }] = await Promise.all([
      supabase
        .from('cart_items')
        .select('id,quantity,unit_price_cents,product_id,products(name,slug,sku)')
        .eq('cart_id', cartId)
        .order('created_at'),
      supabase
        .from('shipping_quotes')
        .select('amount_cents,service_name,estimated_days,expires_at')
        .eq('cart_id', cartId)
        .eq('customer_id', user.id)
        .eq('status', 'valid')
        .gt('expires_at', new Date().toISOString())
        .order('created_at', { ascending: false })
        .limit(1),
    ]);

    if (error) setMessage('Não foi possível carregar o carrinho.');
    setItems((data || []) as unknown as CartItem[]);
    setQuote((quoteData?.[0] || null) as ShippingQuote | null);
    setLoading(false);
  }

  useEffect(() => { void loadCart(); }, []);

  const subtotal = useMemo(
    () => items.reduce((sum, item) => sum + item.quantity * item.unit_price_cents, 0),
    [items]
  );
  const total = subtotal + (quote?.amount_cents || 0);

  async function changeQuantity(id: string, quantity: number) {
    const { error } = await supabase.rpc('set_cart_item_quantity', { p_item_id: id, p_quantity: quantity });
    if (error) setMessage('Quantidade indisponível para o estoque atual.');
    await loadCart();
  }

  async function removeItem(id: string) {
    await supabase.rpc('remove_cart_item', { p_item_id: id });
    await loadCart();
  }

  return (
    <main className="cartPage shell">
      <div className="storeBack"><Link href="/loja">← Continuar comprando</Link></div>
      <header className="storeHero">
        <span className="kicker">CREMENI</span>
        <h1>Carrinho</h1>
        <p>Preço, estoque e frete são revalidados antes do pedido. Nenhum valor de entrega é presumido.</p>
      </header>

      {!userEmail && !loading && (
        <section className="accountCard">
          <p>Entre na sua conta para usar o carrinho persistente.</p>
          <Link className="primary" href="/conta">Entrar</Link>
        </section>
      )}

      {userEmail && (
        <section className="cartCard">
          {loading ? <p>Carregando…</p> : items.length === 0 ? <p>Seu carrinho está vazio.</p> : items.map((item) => (
            <article className="cartLine" key={item.id}>
              <div>
                <strong>{item.products?.name || 'Produto CREMENI'}</strong>
                <small>{item.products?.sku}</small>
              </div>
              <div className="cartQty">
                <button onClick={() => item.quantity > 1 && changeQuantity(item.id, item.quantity - 1)}>−</button>
                <span>{item.quantity}</span>
                <button onClick={() => changeQuantity(item.id, item.quantity + 1)}>+</button>
              </div>
              <strong>{money(item.quantity * item.unit_price_cents)}</strong>
              <button className="textButton" onClick={() => removeItem(item.id)}>Remover</button>
            </article>
          ))}

          {items.length > 0 && (
            <div className="cartSummary">
              <div><span>Subtotal</span><strong>{money(subtotal)}</strong></div>
              <div><span>Frete</span><strong>{quote ? money(quote.amount_cents) : 'A calcular'}</strong></div>
              <div><span>Total</span><strong>{quote ? money(total) : 'A confirmar'}</strong></div>
              {quote && <small>{quote.service_name}{quote.estimated_days != null ? ` · até ${quote.estimated_days} dia(s)` : ''}</small>}
              <Link className="primary" href="/checkout">Continuar para entrega</Link>
              <small>O checkout valida endereço, origem do fornecedor, estoque, dimensões e cotação antes de criar qualquer pedido.</small>
            </div>
          )}
        </section>
      )}

      {message && <p className="accountMessage">{message}</p>}
    </main>
  );
}
