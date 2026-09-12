import Link from 'next/link';
import { formatPrice, getProducts } from '@/lib/catalog';

const logoUrl = 'https://raw.githubusercontent.com/cremeni25/e-commerce/main/wp-content/themes/cremeni-store/assets/images/cremeni-official-wordmark.svg';

export default async function HomePage() {
  const products = await getProducts();
  const selectedProducts = products.slice(0, 4);

  return (
    <main>
      <section className="hero shell">
        <div className="heroCopy">
          <span className="kicker">CREMENI • BOM A QUALQUER HORA</span>
          <h1>Comprar melhor também faz parte da rotina.</h1>
          <p>Uma curadoria própria de esporte, pequenos cuidados para quem está com você e conteúdo que continua útil depois da compra.</p>
          <div className="heroCtas"><Link href="/loja" className="primary">Explorar a loja</Link><Link href="#selecionados" className="secondary">Ver selecionados</Link></div>
          <div className="proof"><span>Curadoria real</span><span>Operação nacional</span><span>Catálogo governado</span></div>
        </div>
        <aside className="heroRail" aria-label="Acessos rápidos">
          <Link href="/loja?vertical=esporte"><small>01</small><strong>Esporte</strong><span>Movimento, treino e prática.</span></Link>
          <Link href="/loja?vertical=pet-mimos"><small>02</small><strong>Pet Mimos</strong><span>Cuidados leves e afetivos.</span></Link>
          <Link href="/guias"><small>03</small><strong>Guias CREMENI</strong><span>Conteúdo curto e útil.</span></Link>
        </aside>
      </section>

      <section className="intentSection">
        <div className="shell">
          <div className="sectionHead"><span className="kicker">COMPRE POR INTENÇÃO</span><h2>Comece pelo que você quer fazer.</h2></div>
          <div className="intentGrid">
            <Link href="/loja?vertical=esporte" className="intentCard"><span>01</span><h3>Quero me movimentar</h3><p>Produtos selecionados por modalidade, função e qualidade.</p><b>Explorar esporte →</b></Link>
            <Link href="/loja?vertical=pet-mimos" className="intentCard accent"><span>02</span><h3>Quero cuidar de quem está comigo</h3><p>Mimos de baixo atrito para acompanhar a rotina com seu pet.</p><b>Explorar pet →</b></Link>
            <Link href="/guias" className="intentCard dark"><span>03</span><h3>Quero aprender algo útil</h3><p>Guias próprios que conectam corpo, mente, hábitos e companhia.</p><b>Conhecer guias →</b></Link>
          </div>
        </div>
      </section>

      <section id="selecionados" className="productSection shell">
        <div className="sectionHead split"><div><span className="kicker">SELEÇÃO CREMENI</span><h2>Um catálogo menor. Mais critério em cada escolha.</h2></div><Link href="/loja">Ver toda a loja →</Link></div>
        <div className="productGrid">
          {selectedProducts.map((product) => (
            <article className="productCard" key={product.id}>
              <Link href={`/produto/${product.slug}`} className="productMedia"><span>{product.modality}</span>{product.imageUrl ? <img src={product.imageUrl} alt={product.name} /> : <div>Imagem real pendente de homologação</div>}</Link>
              <div className="productBody"><small>SELEÇÃO CREMENI · {product.modality}</small><h3><Link href={`/produto/${product.slug}`}>{product.name}</Link></h3><p>{product.personalization}</p><div className="productFooter"><strong>{formatPrice(product.priceCents, product.currency)}</strong><Link href={`/produto/${product.slug}`}>Conhecer →</Link></div></div>
            </article>
          ))}
        </div>
      </section>

      <section className="brandStatement"><div className="shell brandStatementInner"><div><span className="kicker light">POR QUE CREMENI</span><h2>Não queremos ter tudo. Queremos ter motivo para cada item existir aqui.</h2></div><p>Cada produto precisa cumprir uma função clara, ter condição operacional válida e fazer sentido dentro da experiência CREMENI. Sem catálogo inflado. Sem venda sem governança.</p></div></section>

      <footer className="footer"><div className="shell footerGrid"><div className="footerBrand"><img src={logoUrl} alt="CREMENI" /><p>bom a qualquer hora</p></div><div><strong>Comprar</strong><Link href="/loja">Loja</Link><Link href="/loja?vertical=esporte">Esporte</Link><Link href="/loja?vertical=pet-mimos">Pet Mimos</Link></div><div><strong>CREMENI</strong><Link href="/guias">Guias</Link><Link href="/atendimento">Atendimento</Link><Link href="/sobre">Sobre</Link></div></div></footer>
    </main>
  );
}
