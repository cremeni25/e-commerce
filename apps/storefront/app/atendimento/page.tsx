import Link from 'next/link';

export default function SupportPage() {
  return (
    <main className="storePage shell">
      <div className="storeBack"><Link href="/">← Voltar</Link></div>
      <header className="storeHero">
        <span className="kicker">ATENDIMENTO CREMENI</span>
        <h1>Atendimento centralizado pela CREMENI.</h1>
        <p>A experiência de compra, acompanhamento e pós-venda permanece sob responsabilidade da CREMENI, mesmo quando o estoque e a expedição forem realizados por parceiros homologados.</p>
      </header>

      <section className="checkoutGrid">
        <article className="checkoutCard"><h2>Pedidos</h2><p>O acompanhamento de pedido e rastreio será disponibilizado quando a operação logística estiver homologada.</p></article>
        <article className="checkoutCard"><h2>Trocas e devoluções</h2><p>As políticas finais serão publicadas antes da abertura comercial. Nenhuma condição é apresentada aqui de forma provisória ou fictícia.</p></article>
        <article className="checkoutCard"><h2>Canais de contato</h2><p>Os canais oficiais ainda não foram publicados nesta homologação. Eles serão exibidos somente depois de validados para operação real.</p></article>
      </section>
    </main>
  );
}
