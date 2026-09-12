import Link from 'next/link';
import { notFound } from 'next/navigation';
import { formatPrice, getProductBySlug } from '@/lib/catalog';
import AddToCart from '@/components/AddToCart';

function operationalLabel(value: string) {
  const labels: Record<string, string> = {
    awaiting_supplier_stock: 'Estoque do fornecedor em validação',
    validated: 'Validado',
    under_review: 'Em revisão',
    researched_not_contracted: 'Fornecedor pesquisado, contratação pendente',
    not_consolidated: 'Fornecedor ainda não consolidado',
    awaiting_supplier_authorization: 'Imagem aguardando autorização de uso',
    not_validated: 'Ainda não validado',
  };
  return labels[value] || 'Em validação';
}

export default async function ProductPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const product = await getProductBySlug(slug);
  if (!product) notFound();

  const inStock = !product.trackInventory || product.stockQuantity > 0;
  const canBuy = inStock && product.priceCents !== null;

  return (
    <main className="productPage shell">
      <div className="storeBack"><Link href="/loja">← Voltar para a loja</Link></div>

      <section className="productDetail">
        <div className="productDetailMedia">
          <span>{product.modality}</span>
          {product.imageUrl ? (
            <img src={product.imageUrl} alt={product.name} />
          ) : (
            <>
              <strong>Imagem real em homologação</strong>
              <small>{operationalLabel(product.imageStatus)}.</small>
            </>
          )}
        </div>

        <div className="productDetailCopy">
          <span className="kicker">SELEÇÃO CREMENI</span>
          <h1>{product.name}</h1>
          <p className="productLead">{product.shortDescription || product.description}</p>

          <div className="detailFacts">
            <div><span>SKU</span><strong>{product.sku}</strong></div>
            <div><span>Modalidade</span><strong>{product.modality}</strong></div>
            <div><span>Personalização</span><strong>{product.personalization}</strong></div>
          </div>

          <div className="priceHold">
            <small>PREÇO</small>
            <strong>{formatPrice(product.priceCents, product.currency)}</strong>
            <p>{product.priceStatus === 'under_review' ? 'Preço final ainda em revisão.' : inStock ? 'Disponibilidade confirmada no catálogo.' : 'Preço aprovado. Disponibilidade do fornecedor ainda não confirmada.'}</p>
          </div>

          <AddToCart productId={product.id} enabled={canBuy} />

          <div className="trustStack" aria-label="Status da operação">
            <article>
              <span>DISPONIBILIDADE</span>
              <strong>{inStock ? 'Pronto para compra' : 'Venda bloqueada com segurança'}</strong>
              <p>{inStock ? 'Estoque disponível para este item.' : 'A CREMENI não aceita pedido enquanto a disponibilidade real do fornecedor não estiver confirmada.'}</p>
            </article>
            <article>
              <span>LOGÍSTICA</span>
              <strong>Frete calculado somente com origem real</strong>
              <p>CEP de origem, peso e dimensões precisam estar homologados antes de liberar a cotação e o pedido.</p>
            </article>
            <article>
              <span>FORNECIMENTO</span>
              <strong>{operationalLabel(product.supplierStatus)}</strong>
              <p>{product.dropshippingEligible ? 'Elegibilidade para dropshipping identificada; contratação e operação ainda passam por homologação.' : 'Nenhuma condição de dropshipping é presumida sem confirmação comercial.'}</p>
            </article>
          </div>
        </div>
      </section>

      <section className="productExperienceGrid">
        <article className="experienceCard">
          <span className="kicker">POR QUE EXISTE NA CREMENI</span>
          <h2>Curadoria antes de catálogo.</h2>
          <p>Este item foi reservado porque tem função clara dentro de <strong>{product.modality}</strong> e da proposta CREMENI Esporte. A publicação comercial definitiva depende de fornecedor, disponibilidade, logística e experiência de uso coerentes com a marca.</p>
        </article>

        <article className="experienceCard">
          <span className="kicker">PERSONALIZAÇÃO</span>
          <h2>{product.isPersonalizable ? 'Identidade sem perder função.' : 'Produto selecionado pela função.'}</h2>
          <p>{product.isPersonalizable ? 'A arquitetura preserva a personalização do carrinho até o pedido. Regras finais de arte, prazo e produção serão exibidas somente depois da homologação com o fornecedor.' : 'Não existe personalização confirmada para este item. Nenhuma opção será oferecida até existir validação real.'}</p>
        </article>

        <article className="experienceCard">
          <span className="kicker">ENTREGA & DEVOLUÇÃO</span>
          <h2>Promessa só depois de validação.</h2>
          <p>Prazo, modalidade de entrega e regras específicas de troca/devolução serão apresentados com dados reais. A CREMENI não usa frete fictício nem promete prazo antes de validar a operação do SKU.</p>
        </article>
      </section>

      <section className="guideBridge">
        <div>
          <span className="kicker light">GUIAS CREMENI</span>
          <h2>Produto é uma parte da experiência. Conteúdo é o que ajuda a continuar.</h2>
          <p>Os Guias CREMENI conectam produtos a hábitos, rotina, movimento e companhia. Relações específicas produto ↔ guia só serão publicadas quando fizerem sentido e estiverem editorialmente homologadas.</p>
        </div>
        <Link className="secondary guideBridgeCta" href="/guias">Conhecer os Guias CREMENI →</Link>
      </section>
    </main>
  );
}
