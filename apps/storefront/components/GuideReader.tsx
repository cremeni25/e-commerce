'use client';

import { useEffect, useState } from 'react';
import type { GuideSection } from '@/lib/guides';

type GuideReaderProps = {
  sections: GuideSection[];
  intro: string;
};

export default function GuideReader({ sections, intro }: GuideReaderProps) {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);
  const total = sections.length;

  useEffect(() => {
    const hash = window.location.hash;
    const match = hash.match(/^#pagina-(\d+)$/);
    if (!match) return;
    const index = Number(match[1]) - 1;
    if (index >= 0 && index < total) setActiveIndex(index);
  }, [total]);

  function selectPage(index: number) {
    setActiveIndex(index);
    window.history.replaceState(null, '', `#pagina-${index + 1}`);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function goIndex() {
    setActiveIndex(null);
    window.history.replaceState(null, '', window.location.pathname);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  if (activeIndex === null) {
    return (
      <section className="guideContents" aria-label="Índice do guia">
        <div>
          <span className="kicker">ÍNDICE</span>
          <h2>{total} páginas para construir uma rotina possível.</h2>
          <p>{intro}</p>
        </div>
        <nav>
          {sections.map((section, index) => (
            <button key={`${section.title}-${index}`} type="button" onClick={() => selectPage(index)}>
              <span>{String(index + 1).padStart(2, '0')}</span>
              <strong>{section.title}</strong>
            </button>
          ))}
        </nav>
      </section>
    );
  }

  const section = sections[activeIndex];

  return (
    <section className="guideSinglePage" aria-live="polite">
      <article>
        <div className="guidePageMarker">
          <span>PÁGINA</span>
          <strong>{String(activeIndex + 1).padStart(2, '0')}</strong>
          <small>de {String(total).padStart(2, '0')}</small>
        </div>
        <div className="guideSinglePageBody">
          <h2>{section.title}</h2>
          <ul>
            {section.items.map((item) => <li key={item}>{item}</li>)}
          </ul>
        </div>
      </article>

      <nav className="guidePager" aria-label="Navegação entre páginas">
        <button type="button" onClick={goIndex}>⌂ Índice</button>
        <button type="button" onClick={() => selectPage(activeIndex - 1)} disabled={activeIndex === 0}>← Anterior</button>
        <span>{String(activeIndex + 1).padStart(2, '0')} / {String(total).padStart(2, '0')}</span>
        <button type="button" onClick={() => selectPage(activeIndex + 1)} disabled={activeIndex === total - 1}>Próximo →</button>
      </nav>
    </section>
  );
}
