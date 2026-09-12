'use client';

import { useEffect, useMemo, useState } from 'react';

const days = [
  'Dia 1 — passeio curto e observação',
  'Dia 2 — repetir duração confortável',
  'Dia 3 — variar o percurso',
  'Dia 4 — passeio leve e foco em presença',
  'Dia 5 — acrescentar poucos minutos se estiver confortável',
  'Dia 6 — repetir o melhor percurso da semana',
  'Dia 7 — revisar o que funcionou e definir a próxima semana',
];

export default function GuideTracker({ guideSlug }: { guideSlug: string }) {
  const storageKey = `cremeni-guide-progress:${guideSlug}`;
  const [completed, setCompleted] = useState<boolean[]>(() => days.map(() => false));
  const [hydrated, setHydrated] = useState(false);

  useEffect(() => {
    try {
      const raw = window.localStorage.getItem(storageKey);
      if (raw) {
        const parsed = JSON.parse(raw);
        if (Array.isArray(parsed) && parsed.length === days.length) {
          setCompleted(parsed.map(Boolean));
        }
      }
    } catch {
      // Progresso local é opcional. Nenhum dado pessoal é enviado.
    }
    setHydrated(true);
  }, [storageKey]);

  useEffect(() => {
    if (!hydrated) return;
    try {
      window.localStorage.setItem(storageKey, JSON.stringify(completed));
    } catch {
      // Sem impacto na experiência principal.
    }
  }, [completed, hydrated, storageKey]);

  const done = useMemo(() => completed.filter(Boolean).length, [completed]);
  const progress = Math.round((done / days.length) * 100);

  return (
    <section className="guideTracker" aria-labelledby="guide-tracker-title">
      <div className="guideTrackerHead">
        <div>
          <span className="kicker">ACOMPANHAMENTO</span>
          <h2 id="guide-tracker-title">Sua semana Caminhar Juntos</h2>
          <p>Marque cada etapa concluída. O progresso fica apenas neste navegador.</p>
        </div>
        <strong>{progress}%</strong>
      </div>

      <div className="guideProgress" aria-label={`${progress}% concluído`}>
        <span style={{ width: `${progress}%` }} />
      </div>

      <div className="guideChecklist">
        {days.map((day, index) => (
          <label key={day} className={completed[index] ? 'done' : ''}>
            <input
              type="checkbox"
              checked={completed[index]}
              onChange={() => setCompleted((current) => current.map((value, i) => i === index ? !value : value))}
            />
            <span>{day}</span>
          </label>
        ))}
      </div>

      {done === days.length && <p className="guideCelebration">Semana concluída. Revise o que funcionou e defina uma próxima rotina que continue confortável.</p>}
      <small>Nenhuma informação deste acompanhamento é enviada à CREMENI ou armazenada em conta.</small>
    </section>
  );
}
