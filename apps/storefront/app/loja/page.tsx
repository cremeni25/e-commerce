import Link from 'next/link';
import { formatPrice, getProducts } from '@/lib/catalog';

export default async function StorePage({ searchParams }: { searchParams: Promise<{ vertical?: string }> }) {
  const params = await searchParams;
  const selected = params.vertical;
  const products = await getProducts();
  const visible = selected ? products.filter((p) => p.vertical === selected) : products;
  const title = selected === 'pet-mimos' ? 'CREMENI Pet Mimos' : selected === 'esporte' ? 'CREMENI Esporte' : 'Loja CREMENI';

  return (
    <main className="storePage shell">
      <div className="storeBack"><Link href="/">← Voltar</Link></div>
      <header className="storeHero"><span className="kicker">CURADORIA CREMENI</span><h1>{title}</h1><p>Catálogo real, governado e carregado diretamente da base comercial CREMENI.</p></header>
      <nav className="storeTabs"><Link href="/loja">Tudo</Link><Link href="/loja?vertical=esporte">Esporte</Link><Link href="/loja?vertical=pet-mimos">Pet Mimos</Link></nav>
      <div className="storeCount">{visible.length} {visible.length === 1 ? 'item disponível no catálogo' : 'itens disponíveis no catálogo'}</div>
      <section className="productGrid">
        {visible.map((product) => (
          <article className="productCard" key={product.id}>
            <Link href={`/produto/${product.slug}`} className="productMedia"><span>{product.modality}</span>{product.imageUrl ? <img src={product.imageUrl} alt={product.name} /> : <div>Imagem real pendente de homologação</div>}</Link>
            <div className="productBody"><small>SELEÇÃO CREMENI · {product.modality}</small><h2><Link href={`/produto/${product.slug}`}>{product.name}</Link></h2><p>{product.personalization}</p><div className="productFooter"><strong>{formatPrice(product.priceCents, product.currency)}</strong><Link href={`/produto/${product.slug}`}>Ver produto →</Link></div></div>
          </article>
        ))}
      </section>
      {visible.length === 0 && <p>Nenhum produto desta frente foi homologado para publicação ainda.</p>}
    </main>
  );
}
