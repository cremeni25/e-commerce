import type { Metadata } from 'next';
import './globals.css';

export const metadata: Metadata = {
  title: 'CREMENI — bom a qualquer hora',
  description: 'Esporte, Pet Mimos e Guias CREMENI em uma experiência de compra própria, simples e confiável.',
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="pt-BR">
      <body>{children}</body>
    </html>
  );
}
