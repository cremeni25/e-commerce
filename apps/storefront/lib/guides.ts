export type GuideSection = {
  title: string;
  items: string[];
};

export type Guide = {
  id: string;
  guideNumber: number | null;
  slug: string;
  title: string;
  subtitle: string;
  summary: string;
  formatLabel: string;
  estimatedPages: number | null;
  status: 'preview' | 'published';
  reviewRequired: boolean;
  reviewStatus: string;
  collectionName: string;
  collectionSlug: string;
  intro: string;
  sections: GuideSection[];
  safetyNote: string;
};

type GuideRow = {
  id: string;
  guide_number: number | null;
  slug: string;
  title: string;
  subtitle: string | null;
  summary: string | null;
  format_label: string;
  estimated_pages: number | null;
  status: 'preview' | 'published';
  review_required: boolean;
  review_status: string;
  body: {
    intro?: string;
    sections?: GuideSection[];
    safety_note?: string;
  } | null;
  guide_collections: { name: string; slug: string } | null;
};

const supabaseUrl = process.env.NEXT_PUBLIC_SUPABASE_URL || 'https://gstyqhboowperthyfrit.supabase.co';
const supabaseKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY || 'sb_publishable_nWKCmzMYDowkEvLPRYbVxw_7hbBT3Wp';

function headers() {
  return { apikey: supabaseKey, Authorization: `Bearer ${supabaseKey}` };
}

function mapGuide(row: GuideRow): Guide {
  return {
    id: row.id,
    guideNumber: row.guide_number,
    slug: row.slug,
    title: row.title,
    subtitle: row.subtitle || '',
    summary: row.summary || '',
    formatLabel: row.format_label,
    estimatedPages: row.estimated_pages,
    status: row.status,
    reviewRequired: row.review_required,
    reviewStatus: row.review_status,
    collectionName: row.guide_collections?.name || 'GUIAS CREMENI',
    collectionSlug: row.guide_collections?.slug || 'guias',
    intro: row.body?.intro || '',
    sections: Array.isArray(row.body?.sections) ? row.body!.sections! : [],
    safetyNote: row.body?.safety_note || '',
  };
}

const select = 'id,guide_number,slug,title,subtitle,summary,format_label,estimated_pages,status,review_required,review_status,body,guide_collections(name,slug)';

export async function getGuides(): Promise<Guide[]> {
  const response = await fetch(`${supabaseUrl}/rest/v1/guides?select=${encodeURIComponent(select)}&status=in.(preview,published)&order=guide_number.asc.nullslast`, {
    headers: headers(),
    next: { revalidate: 60 },
  });
  if (!response.ok) throw new Error(`Falha ao carregar Guias CREMENI (${response.status}).`);
  const rows = (await response.json()) as GuideRow[];
  return rows.map(mapGuide);
}

export async function getGuideBySlug(slug: string): Promise<Guide | null> {
  const response = await fetch(`${supabaseUrl}/rest/v1/guides?select=${encodeURIComponent(select)}&slug=eq.${encodeURIComponent(slug)}&status=in.(preview,published)&limit=1`, {
    headers: headers(),
    next: { revalidate: 60 },
  });
  if (!response.ok) throw new Error(`Falha ao carregar Guia CREMENI (${response.status}).`);
  const rows = (await response.json()) as GuideRow[];
  return rows[0] ? mapGuide(rows[0]) : null;
}
