import Link from 'next/link';
import { getGuides } from '@/lib/guides';

const collections = [
  ['CREMENI CORPO', 'Movimento, mobilidade e rotina ativa.'],
  ['CREMENI MENTE', 'Hábitos, presença e equilíbrio no dia a dia.'],
  ['CREMENI PET', 'Rotina, enriquecimento e convivência responsável.'],
  ['CREMENI JUNTOS', 'Experiências que aproximam pessoas, movimento e companhia.'],
];

export default async function GuidesPage() {
  const guides = await getGuides();

  return (
    <main className="storePage shell">
      <div className="storeBack"><Link href="/">← Voltar</Link></div>

      <header className="storeHero guideLibraryHero">
        <span className="kicker">GUIAS CREMENI</span>
        <h1>Conteúdo próprio para acompanhar escolhas, rotina e bem-estar.</h1>
        <p>Uma biblioteca editorial conectada à experiência CREMENI: prática, útil, responsável e construída sobre referências rastreáveis.</p>
      </header>

      <section className="guideCollections" aria-label="Coleções Guias CREMENI">
        {collections.map(([name, description]) => (
          <article key={name}>
            <strong>{name}</strong>
            <p>{description}</p>
          </article>
        ))}
      </section>

      <section className="productSection guideLibrary">
        <div className="sectionHead split guideLibraryHead">
          <div>
            <span className="kicker">BIBLIOTECA</span>
            <h2>Guias editoriais para usar, acompanhar e revisitar.</h2>
          </div>
          <span>{guides.length} {guides.length === 1 ? 'guia disponível' : 'guias disponíveis'}</span>
        </div>

        <div className="guideGrid">
          {guides.map((guide) => {
            const pageCount = guide.sections.length || guide.estimatedPages;
            const referenceCount = guide.references.length;

            return (
              <article className="guideCard" key={guide.id}>
                <div className="guideCardTop">
                  <span>{guide.collectionName}</span>
                  <small>{guide.status === 'preview' ? 'PRÉVIA EDITORIAL' : 'PUBLICADO'}</small>
                </div>

                <div className="guideCardIdentity">
                  <p>Guia CREMENI nº {String(guide.guideNumber || '').padStart(3, '0')}</p>
                  <h2>{guide.title}</h2>
                  <h3>{guide.subtitle}</h3>
                </div>

                <p className="guideCardSummary">{guide.summary}</p>

                <div className="guideCardMeta">
                  <span>{pageCount ? `${pageCount} páginas editoriais` : guide.formatLabel}</span>
                  {referenceCount > 0 && <span>{referenceCount} referências</span>}
                  {guide.reviewRequired && <span>{guide.reviewStatus === 'approved' ? 'Revisão aprovada' : 'Revisão profissional pendente'}</span>}
                </div>

                <Link className="primary guideCardCta" href={`/guias/${guide.slug}`}>Abrir guia →</Link>
              </article>
            );
          })}
        </div>
      </section>

      <section className="brandStatement guideLibraryGovernance">
        <div className="brandStatementInner">
          <div>
            <span className="kicker light">GOVERNANÇA</span>
            <h2>Guia não é blog genérico.</h2>
          </div>
          <p>Temas clínicos, veterinários ou de saúde exigem revisão profissional adequada antes de publicação definitiva. A biblioteca pode apoiar produtos, hábitos e relacionamento sem substituir orientação especializada.</p>
        </div>
      </section>
    </main>
  );
}
