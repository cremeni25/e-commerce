import Link from 'next/link';
import { products } from '@/lib/catalog';

export default async function StorePage({ searchParams }: { searchParams: Promise<{ vertical?: string }> }) {
  const params = await searchParams;
  const selected = params.vertical;
  const visible = selected ? products.filter((p) => p.vertical === selected) : products;
  const title = selected === 'pet-mimos' ? 'CREMENI Pet Mimos' : selected === 'esporte' ? 'CREMENI Esporte' : 'Loja CREMENI';

  return (
    <main className="storePage shell">
      <div className="storeBack"><Link href="/">← Voltar</Link></div>
      <header className="storeHero"><span className="kicker">CURADORIA CREMENI</span><h1>{title}</h1><p>Um catálogo enxuto, governado e preparado para crescer sem perder critério.</p></header>
      <nav className="storeTabs"><Link href="/loja">Tudo</Link><Link href="/loja?vertical=esporte">Esporte</Link><Link href="/loja?vertical=pet-mimos">Pet Mimos</Link></nav>
      <div className="storeCount">{visible.length} itens em homologação</div>
      <section className="productGrid">
        {visible.map((product) => (
          <article className="productCard" key={product.slug}>
            <Link href={`/produto/${product.slug}`} className="productMedia"><span>{product.modality}</span><div>Imagem em homologação</div></Link>
            <div className="productBody"><small>{product.supplier}</small><h2><Link href={`/produto/${product.slug}`}>{product.name}</Link></h2><p>{product.personalization}</p><div className="productFooter"><strong>Preço em validação</strong><Link href={`/produto/${product.slug}`}>Ver produto →</Link></div></div>
          </article>
        ))}
      </section>
    </main>
  );
}
