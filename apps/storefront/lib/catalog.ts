export type Product = {
  id: string;
  sku: string;
  slug: string;
  name: string;
  shortDescription: string;
  description: string;
  modality: string;
  vertical: 'esporte' | 'pet-mimos' | 'guias';
  priceCents: number | null;
  currency: string;
  stockQuantity: number;
  trackInventory: boolean;
  isPersonalizable: boolean;
  personalization: string;
  supplier: string;
  imageUrl: string | null;
  availabilityStatus: string;
};

type ProductRow = {
  id: string;
  sku: string;
  slug: string;
  name: string;
  short_description: string | null;
  description: string | null;
  price_cents: number | null;
  currency: string;
  stock_quantity: number;
  track_inventory: boolean;
  is_personalizable: boolean;
  personalization_notes: string | null;
  image_url: string | null;
  metadata: Record<string, unknown> | null;
};

const supabaseUrl = process.env.NEXT_PUBLIC_SUPABASE_URL || 'https://gstyqhboowperthyfrit.supabase.co';
const supabaseKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY || 'sb_publishable_nWKCmzMYDowkEvLPRYbVxw_7hbBT3Wp';

function headers() {
  return { apikey: supabaseKey, Authorization: `Bearer ${supabaseKey}` };
}

function mapProduct(row: ProductRow): Product {
  const metadata = row.metadata || {};
  return {
    id: row.id,
    sku: row.sku,
    slug: row.slug,
    name: row.name,
    shortDescription: row.short_description || '',
    description: row.description || '',
    modality: String(metadata.modality || 'CREMENI'),
    vertical: String(metadata.vertical || 'esporte') as Product['vertical'],
    priceCents: row.price_cents,
    currency: row.currency.trim(),
    stockQuantity: row.stock_quantity,
    trackInventory: row.track_inventory,
    isPersonalizable: row.is_personalizable,
    personalization: row.personalization_notes || (row.is_personalizable ? 'Personalização disponível' : 'Não se aplica'),
    supplier: String(metadata.supplier || 'Fornecedor nacional validado'),
    imageUrl: row.image_url,
    availabilityStatus: String(metadata.availability_status || 'unknown'),
  };
}

const select = 'id,sku,slug,name,short_description,description,price_cents,currency,stock_quantity,track_inventory,is_personalizable,personalization_notes,image_url,metadata';

export async function getProducts(): Promise<Product[]> {
  const response = await fetch(`${supabaseUrl}/rest/v1/products?select=${select}&status=eq.active&order=sku.asc`, {
    headers: headers(),
    next: { revalidate: 60 },
  });
  if (!response.ok) throw new Error(`Falha ao carregar catálogo CREMENI (${response.status}).`);
  const rows = (await response.json()) as ProductRow[];
  return rows.map(mapProduct);
}

export async function getProductBySlug(slug: string): Promise<Product | null> {
  const response = await fetch(`${supabaseUrl}/rest/v1/products?select=${select}&status=eq.active&slug=eq.${encodeURIComponent(slug)}&limit=1`, {
    headers: headers(),
    next: { revalidate: 60 },
  });
  if (!response.ok) throw new Error(`Falha ao carregar produto CREMENI (${response.status}).`);
  const rows = (await response.json()) as ProductRow[];
  return rows[0] ? mapProduct(rows[0]) : null;
}

export function formatPrice(priceCents: number | null, currency = 'BRL') {
  if (priceCents === null) return 'Preço em validação';
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency }).format(priceCents / 100);
}
