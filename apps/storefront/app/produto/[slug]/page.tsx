import Link from 'next/link';
import { notFound } from 'next/navigation';
import { formatPrice, getProductBySlug } from '@/lib/catalog';
import AddToCart from '@/components/AddToCart';

export default async function ProductPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = await getProductBySlug(slug);
  if (!product) notFound();

  const inStock = !product.trackInventory || product.stockQuantity > 0;

  return (
    <main className="productPage shell">
      <div className="storeBack"><Link href="/loja">← Voltar para a loja</Link></div>
      <section className="productDetail">
        <div className="productDetailMedia"><span>{product.modality}</span>{product.imageUrl ? <img src={product.imageUrl} alt={product.name} /> : <><strong>Imagem real em homologação</strong><small>Fotografia pendente de validação visual e autorização de uso.</small></>}</div>
        <div className="productDetailCopy">
          <span className="kicker">SELEÇÃO CREMENI</span>
          <h1>{product.name}</h1>
          <p className="productLead">{product.shortDescription || product.description}</p>
          <div className="detailFacts"><div><span>SKU</span><strong>{product.sku}</strong></div><div><span>Modalidade</span><strong>{product.modality}</strong></div><div><span>Personalização</span><strong>{product.personalization}</strong></div></div>
          <div className="priceHold"><small>PREÇO</small><strong>{formatPrice(product.priceCents, product.currency)}</strong><p>{inStock ? 'Disponibilidade confirmada no catálogo.' : 'Preço aprovado. Disponibilidade do fornecedor ainda não confirmada.'}</p></div>
          <AddToCart productId={product.id} enabled={inStock && product.priceCents !== null} />
        </div>
      </section>
    </main>
  );
}
