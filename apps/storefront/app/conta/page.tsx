'use client';

import Link from 'next/link';
import { FormEvent, useEffect, useState } from 'react';
import { supabase } from '@/lib/supabase-browser';

type Mode = 'login' | 'register';

function friendlyAuthError(message: string) {
  const value = message.toLowerCase();
  if (value.includes('invalid login credentials')) return 'E-mail ou senha não conferem.';
  if (value.includes('user already registered')) return 'Este e-mail já possui uma conta. Use Entrar.';
  if (value.includes('password should be at least')) return 'A senha precisa ter pelo menos 6 caracteres.';
  if (value.includes('unable to validate email') || value.includes('invalid email')) return 'Informe um e-mail válido.';
  if (value.includes('signup is disabled')) return 'Criação de contas está temporariamente indisponível.';
  if (value.includes('rate limit')) return 'Muitas tentativas em sequência. Aguarde alguns instantes e tente novamente.';
  return `Não foi possível concluir: ${message}`;
}

export default function AccountPage() {
  const [mode, setMode] = useState<Mode>('login');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [userEmail, setUserEmail] = useState<string | null>(null);
  const [message, setMessage] = useState('');
  const [busy, setBusy] = useState(false);

  useEffect(() => {
    supabase.auth.getUser().then(({ data }) => setUserEmail(data.user?.email || null));
    const { data } = supabase.auth.onAuthStateChange((_event, session) => setUserEmail(session?.user.email || null));
    return () => data.subscription.unsubscribe();
  }, []);

  async function submit(event: FormEvent) {
    event.preventDefault();
    setMessage('');

    const normalizedEmail = email.trim().toLowerCase();
    if (!normalizedEmail) {
      setMessage('Informe seu e-mail.');
      return;
    }
    if (password.length < 6) {
      setMessage('A senha precisa ter pelo menos 6 caracteres.');
      return;
    }
    if (mode === 'register' && password !== confirmPassword) {
      setMessage('As senhas não coincidem.');
      return;
    }

    setBusy(true);
    try {
      if (mode === 'login') {
        const { error } = await supabase.auth.signInWithPassword({ email: normalizedEmail, password });
        setMessage(error ? friendlyAuthError(error.message) : 'Acesso realizado.');
        return;
      }

      const { data, error } = await supabase.auth.signUp({ email: normalizedEmail, password });
      if (error) {
        setMessage(friendlyAuthError(error.message));
        return;
      }

      if (data.session) {
        setMessage('Conta criada e acesso realizado.');
      } else {
        setMessage('Conta criada. Verifique seu e-mail para confirmar o cadastro.');
      }
    } finally {
      setBusy(false);
    }
  }

  async function signOut() {
    await supabase.auth.signOut();
    setMessage('Sessão encerrada.');
  }

  function changeMode(next: Mode) {
    setMode(next);
    setMessage('');
    setPassword('');
    setConfirmPassword('');
  }

  return (
    <main className="accountPage shell">
      <div className="storeBack"><Link href="/">← Voltar</Link></div>
      <section className="accountCard">
        <span className="kicker">MINHA CREMENI</span>
        <h1>{userEmail ? 'Conta' : mode === 'login' ? 'Entrar' : 'Criar conta'}</h1>

        {userEmail ? (
          <div className="accountLogged">
            <p>Conectado como <strong>{userEmail}</strong>.</p>
            <div className="heroCtas">
              <Link className="primary" href="/carrinho">Abrir carrinho</Link>
              <button className="secondary" onClick={signOut}>Sair</button>
            </div>
          </div>
        ) : (
          <>
            <form onSubmit={submit} className="accountForm">
              <label>
                E-mail
                <input type="email" required value={email} onChange={(e) => setEmail(e.target.value)} autoComplete="email" />
              </label>
              <label>
                Senha
                <input type="password" minLength={6} required value={password} onChange={(e) => setPassword(e.target.value)} autoComplete={mode === 'login' ? 'current-password' : 'new-password'} />
              </label>
              {mode === 'register' && (
                <label>
                  Confirmar senha
                  <input type="password" minLength={6} required value={confirmPassword} onChange={(e) => setConfirmPassword(e.target.value)} autoComplete="new-password" />
                </label>
              )}
              <button className="primary" type="submit" disabled={busy}>{busy ? 'Processando…' : mode === 'login' ? 'Entrar' : 'Criar minha conta'}</button>
            </form>

            {mode === 'login' ? (
              <button className="secondary accountModeButton" type="button" onClick={() => changeMode('register')}>Ainda não tenho conta</button>
            ) : (
              <button className="secondary accountModeButton" type="button" onClick={() => changeMode('login')}>Já tenho conta</button>
            )}
          </>
        )}

        {message && <p className="accountMessage" role="status">{message}</p>}
      </section>
    </main>
  );
}
