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
          <p>As {totalPages} páginas já existem como experiência digital. O conteúdo permanece em prévia até a revisão profissional prevista para temas de saúde e bem-estar.</p>
        </section>
      )}

      <GuideReader sections={guide.sections} intro={guide.intro} guideSlug={guide.slug} />

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
