'use client';

import { useEffect, useState } from 'react';
import GuideTracker from '@/components/GuideTracker';
import type { GuideSection } from '@/lib/guides';

type GuideReaderProps = {
  sections: GuideSection[];
  intro: string;
  guideSlug: string;
};

export default function GuideReader({ sections, intro, guideSlug }: GuideReaderProps) {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);
  const total = sections.length;

  useEffect(() => {
    const hash = window.location.hash;
    const match = hash.match(/^#pagina-(\d+)$/);
    if (!match) return;
    const index = Number(match[1]) - 1;
    if (index >= 0 && index < total) {
      setActiveIndex(index);
      window.setTimeout(() => document.getElementById('guide-reader')?.scrollIntoView({ block: 'start' }), 60);
    }
  }, [total]);

  function moveToReader() {
    window.setTimeout(() => document.getElementById('guide-reader')?.scrollIntoView({ behavior: 'smooth', block: 'start' }), 40);
  }

  function selectPage(index: number) {
    if (index < 0 || index >= total) return;
    setActiveIndex(index);
    window.history.replaceState(null, '', `#pagina-${index + 1}`);
    moveToReader();
  }

  function goIndex() {
    setActiveIndex(null);
    window.history.replaceState(null, '', window.location.pathname);
    moveToReader();
  }

  if (activeIndex === null) {
    return (
      <section id="guide-reader" className="guideContents" aria-label="Índice do guia">
        <div className="guideContentsIntro">
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
  const showTracker = guideSlug === 'caminhar-juntos' && activeIndex === 9;
  const bullets = section.keypoints?.length ? section.keypoints : (section.items || []);

  const Pager = ({ compact = false }: { compact?: boolean }) => (
    <nav className={compact ? 'guidePager guidePagerTop' : 'guidePager'} aria-label="Navegação entre páginas">
      <button type="button" onClick={goIndex}>⌂ Índice</button>
      <button type="button" onClick={() => selectPage(activeIndex - 1)} disabled={activeIndex === 0}>← Anterior</button>
      <span>{String(activeIndex + 1).padStart(2, '0')} / {String(total).padStart(2, '0')}</span>
      <button type="button" onClick={() => selectPage(activeIndex + 1)} disabled={activeIndex === total - 1}>Próximo →</button>
    </nav>
  );

  return (
    <section id="guide-reader" className="guideSinglePage" aria-live="polite">
      <Pager compact />

      <article className="guideFeaturePage">
        <aside className="guidePageRail">
          <div className="guidePageMarker">
            <span>PÁGINA</span>
            <strong>{String(activeIndex + 1).padStart(2, '0')}</strong>
            <small>de {String(total).padStart(2, '0')}</small>
          </div>
          <div className="guidePageTheme">CAMINHAR JUNTOS</div>
        </aside>

        <div className="guideSinglePageBody">
          <header className="guideArticleHeader">
            <span className="kicker">GUIA CREMENI</span>
            <h2>{section.title}</h2>
            {section.lead && <p className="guideLead">{section.lead}</p>}
          </header>

          <div className="guideReadingGrid">
            <div className="guideReadingMain">
              {(section.paragraphs || []).map((paragraph) => <p key={paragraph}>{paragraph}</p>)}

              {bullets.length > 0 && (
                <section className="guideKeyPoints">
                  <span className="guideMiniLabel">EM FOCO</span>
                  <ul>
                    {bullets.map((item) => <li key={item}>{item}</li>)}
                  </ul>
                </section>
              )}
            </div>

            <aside className="guidePracticePanel">
              <span className="guideMiniLabel">APLICAÇÃO PRÁTICA</span>
              <strong>Leve para a próxima caminhada</strong>
              <p>{section.practice || 'Observe a rotina real e ajuste apenas o que for necessário para manter conforto e consistência.'}</p>

              {section.sources && section.sources.length > 0 && (
                <div className="guideSources">
                  <span className="guideMiniLabel">BASE EDITORIAL</span>
                  {section.sources.map((source) => <small key={source}>{source}</small>)}
                </div>
              )}
            </aside>
          </div>

          {showTracker && <GuideTracker guideSlug={guideSlug} />}
        </div>
      </article>

      <Pager />
    </section>
  );
}
