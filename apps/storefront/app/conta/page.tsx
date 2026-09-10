'use client';

import Link from 'next/link';
import { FormEvent, useEffect, useState } from 'react';
import { supabase } from '@/lib/supabase-browser';

export default function AccountPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [userEmail, setUserEmail] = useState<string | null>(null);
  const [message, setMessage] = useState('');

  useEffect(() => {
    supabase.auth.getUser().then(({ data }) => setUserEmail(data.user?.email || null));
    const { data } = supabase.auth.onAuthStateChange((_event, session) => setUserEmail(session?.user.email || null));
    return () => data.subscription.unsubscribe();
  }, []);

  async function signIn(event: FormEvent) {
    event.preventDefault();
    setMessage('');
    const { error } = await supabase.auth.signInWithPassword({ email, password });
    setMessage(error ? 'Não foi possível entrar. Verifique e-mail e senha.' : 'Acesso realizado.');
  }

  async function signUp() {
    setMessage('');
    const { error } = await supabase.auth.signUp({ email, password });
    setMessage(error ? 'Não foi possível criar a conta.' : 'Cadastro recebido. Se solicitado, confirme seu e-mail.');
  }

  async function signOut() {
    await supabase.auth.signOut();
    setMessage('Sessão encerrada.');
  }

  return (
    <main className="accountPage shell">
      <div className="storeBack"><Link href="/">← Voltar</Link></div>
      <section className="accountCard">
        <span className="kicker">MINHA CREMENI</span>
        <h1>Conta</h1>
        {userEmail ? (
          <div className="accountLogged"><p>Conectado como <strong>{userEmail}</strong>.</p><div className="heroCtas"><Link className="primary" href="/carrinho">Abrir carrinho</Link><button className="secondary" onClick={signOut}>Sair</button></div></div>
        ) : (
          <form onSubmit={signIn} className="accountForm">
            <label>E-mail<input type="email" required value={email} onChange={(e) => setEmail(e.target.value)} autoComplete="email" /></label>
            <label>Senha<input type="password" minLength={6} required value={password} onChange={(e) => setPassword(e.target.value)} autoComplete="current-password" /></label>
            <button className="primary" type="submit">Entrar</button>
            <button className="secondary" type="button" onClick={signUp}>Criar conta</button>
          </form>
        )}
        {message && <p className="accountMessage">{message}</p>}
      </section>
    </main>
  );
}
