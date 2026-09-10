import Link from 'next/link';
import { notFound } from 'next/navigation';
import { products } from '@/lib/catalog';

export function generateStaticParams() {
  return products.map((product) => ({ slug: product.slug }));
}

export default async function ProductPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = products.find((item) => item.slug === slug);
  if (!product) notFound();

  return (
    <main className="productPage shell">
      <div className="storeBack"><Link href="/loja">← Voltar para a loja</Link></div>
      <section className="productDetail">
        <div className="productDetailMedia"><span>{product.modality}</span><strong>Imagem real em homologação</strong><small>A publicação da fotografia depende de autorização de uso e validação visual.</small></div>
        <div className="productDetailCopy">
          <span className="kicker">SELEÇÃO CREMENI</span>
          <h1>{product.name}</h1>
          <p className="productLead">Produto selecionado para integrar o catálogo CREMENI com condição comercial e logística ainda em validação final.</p>
          <div className="detailFacts"><div><span>Modalidade</span><strong>{product.modality}</strong></div><div><span>Personalização</span><strong>{product.personalization}</strong></div><div><span>Origem</span><strong>{product.supplier}</strong></div></div>
          <div className="priceHold"><small>PREÇO</small><strong>Em homologação</strong><p>A venda permanece bloqueada até a regra comercial, o frete e a operação de dropshipping estarem aprovados.</p></div>
          <button className="disabledBuy" disabled>Compra ainda não liberada</button>
        </div>
      </section>
    </main>
  );
}
