import Link from 'next/link';

export default function GuidesPage() {
  return (
    <main className="storePage shell">
      <div className="storeBack"><Link href="/">← Voltar</Link></div>
      <header className="storeHero">
        <span className="kicker">GUIAS CREMENI</span>
        <h1>Conteúdo próprio para continuar útil depois da compra.</h1>
        <p>Os Guias CREMENI são uma frente proprietária de relacionamento, autoridade, educação e apoio ao catálogo.</p>
      </header>

      <section className="checkoutGrid">
        <article className="checkoutCard">
          <span className="kicker">PILOTO DEFINIDO</span>
          <h2>Guia CREMENI nº 001 — Caminhar Juntos</h2>
          <p>Conceito: atividade conjunta entre pessoa e pet, conectando movimento, companhia e rotina.</p>
          <p>O artefato final ainda não foi publicado. Esta página registra apenas a decisão canônica já aprovada.</p>
        </article>

        <aside className="checkoutCard">
          <h2>Coleções previstas</h2>
          <p><strong>CREMENI CORPO</strong><br />Movimento, rotina ativa e prática.</p>
          <p><strong>CREMENI MENTE</strong><br />Hábitos, equilíbrio e presença.</p>
          <p><strong>CREMENI PET</strong><br />Convivência, estímulo e cuidados cotidianos.</p>
          <p><strong>CREMENI JUNTOS</strong><br />Experiências que conectam pessoas, atividade e companhia.</p>
        </aside>
      </section>

      <section className="brandStatement">
        <div className="shell brandStatementInner">
          <div><span className="kicker light">GOVERNANÇA</span><h2>Guia não é blog genérico.</h2></div>
          <p>Temas clínicos, veterinários ou de saúde exigem revisão profissional adequada antes de publicação. Os guias podem se relacionar ao catálogo e ao cross-sell, mas não substituem validação técnica especializada.</p>
        </div>
      </section>
    </main>
  );
}
