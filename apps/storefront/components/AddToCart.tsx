'use client';

import Link from 'next/link';
import { useState } from 'react';
import { supabase } from '@/lib/supabase-browser';

export default function AddToCart({ productId, enabled }: { productId: string; enabled: boolean }) {
  const [message, setMessage] = useState('');
  const [working, setWorking] = useState(false);

  async function add() {
    setWorking(true);
    setMessage('');
    const { data: auth } = await supabase.auth.getUser();
    if (!auth.user) {
      setMessage('Entre na sua conta para adicionar ao carrinho.');
      setWorking(false);
      return;
    }
    const { error } = await supabase.rpc('add_cart_item', { p_product_id: productId, p_quantity: 1, p_personalization: {} });
    setMessage(error ? (error.message.includes('insufficient_stock') ? 'Disponibilidade ainda não confirmada.' : 'Não foi possível adicionar agora.') : 'Produto adicionado ao carrinho.');
    setWorking(false);
  }

  if (!enabled) return <button className="disabledBuy" disabled>Aguardando disponibilidade</button>;

  return <div className="buyBlock"><button className="primary" disabled={working} onClick={add}>{working ? 'Adicionando…' : 'Adicionar ao carrinho'}</button>{message && <p>{message} {message.startsWith('Entre') && <Link href="/conta">Acessar conta →</Link>}</p>}</div>;
}
