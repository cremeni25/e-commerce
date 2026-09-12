import Link from 'next/link';
import { notFound } from 'next/navigation';
import GuideReader from '@/components/GuideReader';
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
          <p>Conteúdo pesquisado e estruturado com referências internacionais. A publicação definitiva continua condicionada à revisão profissional dos temas de saúde, segurança e bem-estar.</p>
        </section>
      )}

      <GuideReader sections={guide.sections} intro={guide.intro} guideSlug={guide.slug} />

      {guide.safetyNote && (
        <section className="guideSafety">
          <strong>Uso responsável</strong>
          <p>{guide.safetyNote}</p>
        </section>
      )}

      {guide.references.length > 0 && (
        <section className="guideReferenceLibrary">
          <div>
            <span className="kicker">BASE EDITORIAL INTERNACIONAL</span>
            <h2>Pesquisa rastreável, não opinião solta.</h2>
            <p>As referências abaixo sustentam a construção editorial desta prévia. A CREMENI usa fontes de saúde pública, medicina veterinária e bem-estar animal reconhecidas internacionalmente.</p>
          </div>
          <div className="guideReferenceGrid">
            {guide.references.map((reference) => (
              <a key={`${reference.organization}-${reference.title}`} href={reference.url} target="_blank" rel="noreferrer">
                <span>{reference.organization}</span>
                <strong>{reference.title}</strong>
                <small>Abrir fonte ↗</small>
              </a>
            ))}
          </div>
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
