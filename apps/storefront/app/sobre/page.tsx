import Link from 'next/link';

export default function AboutPage() {
  return (
    <main className="storePage shell">
      <div className="storeBack"><Link href="/">← Voltar</Link></div>
      <header className="storeHero">
        <span className="kicker">SOBRE A CREMENI</span>
        <h1>Uma vida mais ativa, equilibrada e conectada.</h1>
        <p>CREMENI é uma marca de curadoria. Produtos, conteúdo e experiências entram apenas quando existe um motivo claro para fazerem parte da marca.</p>
      </header>

      <section className="checkoutGrid">
        <article className="checkoutCard"><h2>CREMENI Esporte</h2><p>Artigos esportivos selecionados por utilidade, contexto, competitividade e governança comercial.</p></article>
        <article className="checkoutCard"><h2>CREMENI Pet Mimos</h2><p>Frente complementar, emocional e recorrente, com catálogo reduzido e forte relação com contexto de uso e composição do carrinho.</p></article>
        <article className="checkoutCard"><h2>Guias CREMENI</h2><p>Conteúdo proprietário para relacionamento, autoridade, educação e continuidade da experiência depois da compra.</p></article>
      </section>

      <section className="brandStatement">
        <div className="shell brandStatementInner"><div><span className="kicker light">REGRA EDITORIAL</span><h2>Por que isso existe na CREMENI?</h2></div><p>Se a resposta não for clara, o produto ou conteúdo não entra no catálogo.</p></div>
      </section>
    </main>
  );
}
