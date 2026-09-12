import Link from 'next/link';
import { notFound } from 'next/navigation';
import GuideTracker from '@/components/GuideTracker';
import { getGuideBySlug } from '@/lib/guides';

export default async function GuidePage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const guide = await getGuideBySlug(slug);
  if (!guide) notFound();

  const isPreview = guide.status === 'preview';
  const totalPages = guide.sections.length;

  return (
    <main className="guidePage shell">
      <div className="storeBack"><Link href="/guias">← Voltar aos Guias</Link></div>

      <header className="guideHero">
        <div>
          <span className="kicker">{guide.collectionName}</span>
          <p className="guideNumber">Guia CREMENI nº {String(guide.guideNumber || '').padStart(3, '0')}</p>
          <h1>{guide.title}</h1>
          <h2>{guide.subtitle}</h2>
          <p>{guide.summary}</p>
        </div>
        <aside>
          <strong>{guide.formatLabel}</strong>
          <span>{totalPages} páginas editoriais disponíveis</span>
          <span>{isPreview ? 'Prévia editorial' : 'Publicado'}</span>
          {guide.reviewRequired && <span>Revisão profissional: {guide.reviewStatus === 'approved' ? 'aprovada' : 'pendente'}</span>}
        </aside>
      </header>

      {isPreview && (
        <section className="guidePreviewNotice">
          <strong>Prévia editorial em homologação</strong>
          <p>As 12 páginas abaixo já existem como experiência digital. O conteúdo permanece em prévia até a revisão profissional prevista para temas de saúde e bem-estar.</p>
        </section>
      )}

      <section className="guideContents" id="indice-topo" aria-label="Índice do guia">
        <div>
          <span className="kicker">ÍNDICE</span>
          <h2>12 páginas para construir uma rotina possível.</h2>
          <p>{guide.intro}</p>
        </div>
        <nav>
          {guide.sections.map((section, index) => (
            <a key={`${section.title}-${index}`} href={`#pagina-${index + 1}`}>
              <span>{String(index + 1).padStart(2, '0')}</span>
              <strong>{section.title}</strong>
            </a>
          ))}
        </nav>
      </section>

      <section className="guideEditorial guidePages">
        {guide.sections.map((section, index) => (
          <article id={`pagina-${index + 1}`} key={`${section.title}-${index}`}>
            <div className="guidePageMarker">
              <span>PÁGINA</span>
              <strong>{String(index + 1).padStart(2, '0')}</strong>
              <small>de {String(totalPages).padStart(2, '0')}</small>
            </div>
            <div>
              <h2>{section.title}</h2>
              <ul>
                {section.items.map((item) => <li key={item}>{item}</li>)}
              </ul>
              <a className="guideBackToIndex" href="#indice-topo">Voltar ao índice ↑</a>
            </div>
          </article>
        ))}
      </section>

      {guide.slug === 'caminhar-juntos' && <GuideTracker guideSlug={guide.slug} />}

      {guide.safetyNote && (
        <section className="guideSafety">
          <strong>Uso responsável</strong>
          <p>{guide.safetyNote}</p>
        </section>
      )}

      <section className="brandStatement guideStatement">
        <div className="brandStatementInner">
          <div><span className="kicker light">GUIAS CREMENI</span><h2>Conteúdo que continua útil depois da compra.</h2></div>
          <p>Os guias serão conectados a produtos e situações reais da rotina, sem substituir orientação profissional quando ela for necessária.</p>
        </div>
      </section>
    </main>
  );
}
