'use client';

import Link from 'next/link';
import { useEffect, useMemo, useState } from 'react';
import { supabase } from '@/lib/supabase-browser';

const logoUrl = 'https://raw.githubusercontent.com/cremeni25/e-commerce/main/wp-content/themes/cremeni-store/assets/images/cremeni-official-wordmark.svg';

export default function StoreHeader() {
  const [email, setEmail] = useState<string | null>(null);
  const [cartCount, setCartCount] = useState(0);

  const identity = useMemo(() => {
    if (!email) return null;
    const local = email.split('@')[0] || 'cliente';
    return local
      .split(/[._-]+/)
      .filter(Boolean)
      .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
      .join(' ');
  }, [email]);

  async function refreshCart(userId?: string) {
    if (!userId) {
      setCartCount(0);
      return;
    }

    const { data: carts } = await supabase
      .from('carts')
      .select('id')
      .eq('customer_id', userId)
      .eq('status', 'open')
      .order('created_at', { ascending: false })
      .limit(1);

    const cartId = carts?.[0]?.id;
    if (!cartId) {
      setCartCount(0);
      return;
    }

    const { data: items } = await supabase
      .from('cart_items')
      .select('quantity')
      .eq('cart_id', cartId);

    setCartCount((items || []).reduce((sum, item) => sum + Number(item.quantity || 0), 0));
  }

  useEffect(() => {
    let active = true;

    supabase.auth.getUser().then(({ data }) => {
      if (!active) return;
      const user = data.user;
      setEmail(user?.email || null);
      void refreshCart(user?.id);
    });

    const { data: listener } = supabase.auth.onAuthStateChange((_event, session) => {
      if (!active) return;
      setEmail(session?.user.email || null);
      void refreshCart(session?.user.id);
    });

    const handleFocus = () => {
      supabase.auth.getUser().then(({ data }) => void refreshCart(data.user?.id));
    };
    window.addEventListener('focus', handleFocus);

    return () => {
      active = false;
      listener.subscription.unsubscribe();
      window.removeEventListener('focus', handleFocus);
    };
  }, []);

  return (
    <header className="topbar">
      <div className="shell nav">
        <Link href="/" className="brand" aria-label="CREMENI — início">
          <img src={logoUrl} alt="CREMENI" />
        </Link>

        <nav className="navlinks" aria-label="Navegação principal">
          <Link href="/loja">Loja</Link>
          <Link href="/loja?vertical=esporte">Esporte</Link>
          <Link href="/loja?vertical=pet-mimos">Pet Mimos</Link>
          <Link href="/guias">Guias</Link>
        </nav>

        <div className="actions">
          {email ? (
            <Link href="/conta" className="sessionIdentity" aria-label={`Conta conectada: ${email}`}>
              <span className="sessionDot" aria-hidden="true" />
              <span className="sessionAvatar" aria-hidden="true">{(identity || 'C').charAt(0)}</span>
              <span className="sessionText"><small>Conectado</small><strong>{identity || email}</strong></span>
            </Link>
          ) : (
            <Link href="/conta" className="sessionIdentity guestIdentity">
              <span className="sessionAvatar" aria-hidden="true">◯</span>
              <span className="sessionText"><small>Minha CREMENI</small><strong>Entrar</strong></span>
            </Link>
          )}

          <Link href="/carrinho" className="cart">Carrinho <span>{cartCount}</span></Link>
        </div>
      </div>
    </header>
  );
}
