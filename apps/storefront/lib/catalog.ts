export type Product = {
  slug: string;
  name: string;
  modality: string;
  vertical: 'esporte' | 'pet-mimos';
  price: number | null;
  status: 'homologacao' | 'ativo';
  personalization: string;
  supplier: string;
};

export const products: Product[] = [
  {
    slug: 'raquete-beach-tennis-personalizada',
    name: 'Raquete Beach Tennis Personalizada',
    modality: 'Beach Tennis',
    vertical: 'esporte',
    price: null,
    status: 'homologacao',
    personalization: 'Personalização disponível',
    supplier: 'Fornecedor nacional validado',
  },
  {
    slug: 'colchonete-treino-yoga-personalizado',
    name: 'Colchonete Treino & Yoga Personalizado',
    modality: 'Yoga & Pilates',
    vertical: 'esporte',
    price: null,
    status: 'homologacao',
    personalization: 'Personalização disponível',
    supplier: 'Fornecedor nacional validado',
  },
  {
    slug: 'kit-agilidade-8-cones-4-bastoes',
    name: 'Kit Agilidade 8 Cones + 4 Bastões',
    modality: 'Treino funcional',
    vertical: 'esporte',
    price: null,
    status: 'homologacao',
    personalization: 'Sem personalização obrigatória',
    supplier: 'Fornecedor nacional validado',
  },
  {
    slug: 'prancha-aprendizagem-natacao-personalizada',
    name: 'Prancha de Aprendizagem de Natação Personalizada',
    modality: 'Natação',
    vertical: 'esporte',
    price: null,
    status: 'homologacao',
    personalization: 'Personalização disponível',
    supplier: 'Fornecedor nacional validado',
  },
];
