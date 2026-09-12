# CREMENI E-commerce — Canônico Operacional (set/2026)

Este documento registra as decisões operacionais, comerciais e técnicas que não devem ser rediscutidas sem decisão explícita posterior.

## Identidade e escopo
- Marca: CREMENI.
- Domínio oficial: cremeni.com.br.
- Identidade visual: azul #1f295d, amarelo #f0e939, logo oficial preservado e lockup “cremeni — bom a qualquer hora”.
- Conceito guarda-chuva: uma vida mais ativa, equilibrada e conectada.
- Frentes atuais: CREMENI ESPORTE, CREMENI PET MIMOS e GUIAS CREMENI.
- PET PREMIUM permanece futuro.
- Regra editorial: antes de publicar, responder “Por que isso existe na CREMENI?”.

## Regra econômica SPORT
R$ 10,00 é apenas a diferença absoluta mínima sobre o custo/base drop aprovado. Não é meta, teto nem lucro líquido. Se o mercado permitir, capturar diferença maior. Se o mercado não comportar o piso, bloquear/revisar o produto.

## Núcleo SPORT reservado
1. Raquete Beach Tennis Personalizada — custo R$240,00 — piso R$250,00 — preço R$289,90.
2. Tapete/Colchonete Academia/Pilates/Yoga — custo R$82,50 — piso R$92,50 — preço R$104,90.
3. Garrafa Térmica Inox 750 ml — custo R$51,66 — piso R$61,66 — preço R$67,90.
4. Corda Speed Personalizada — custo R$51,75 — piso R$61,75 — preço R$67,90.
5. Kit Agilidade 8 cones + 4 bastões — custo R$83,85 — piso R$93,85 — preço R$104,90.
6. Faixa Personalizada Artes Marciais/Jiu-Jitsu — custo R$74,25 — piso R$84,25 — preço R$94,90.
7. Bola Futsal Topper Slick Cup — custo R$76,28 — piso R$86,28 — preço final em revisão.
8. Bolsa Térmica Esportiva/Lancheira Grande Personalizada — custo R$100,24 — piso R$110,24 — preço R$124,90.
9. Prancha Personalizada para Aprendizado de Natação — custo R$112,50 — piso R$122,50 — preço R$139,90.
10. Bola Penalty Beach Pro — custo R$263,78 — piso R$273,78 — preço R$319,90.
11º SKU permanece aberto e não pode ser preenchido automaticamente.

## Fornecedores
Pesquisados: Dinka, Dropify, Drop Nacional/Dropi, Patzo, Dropet e ComfortPet.
Dinka é a fonte pesquisada mais forte para vários preços SPORT, mas não está contratada. Não expor como parceria homologada. Preço público da Dropet não pode ser tratado como custo drop oficial sem confirmação comercial.

## PET MIMOS
Modelo comercial decidido, porém catálogo final ainda não homologado/materializado. Trabalhar com 6–10 produtos fortes, não catálogo massivo. Precificação PET considera percentual, mercado, logística, contribuição ao carrinho, recorrência, fornecedor e custo real. A regra de R$10 do SPORT não se aplica automaticamente. Preferir origens logísticas compartilhadas quando fizer sentido.

## GUIAS CREMENI
Conteúdo proprietário de autoridade, relacionamento, retenção, diferenciação e apoio ao catálogo. Coleções: CREMENI CORPO, CREMENI MENTE, CREMENI PET e CREMENI JUNTOS. Primeiro piloto definido: “Guia CREMENI nº 001 — Caminhar Juntos”. Temas clínicos, veterinários ou de saúde exigem revisão profissional adequada antes da publicação.

## Governança comercial
Fluxo: fornecedor → validação CREMENI → governança → catálogo aprovado → loja → cliente.
Antes da venda validar: fornecedor, custo, preço, margem mínima, estoque, logística, prazo, frete, descrição, imagem, coerência com marca, personalização quando houver e política aplicável.
Estoque real não confirmado = venda bloqueada. Backorder não é solução automática.
Pagamento real permanece desabilitado até estoque, frete e logística estarem homologados.

## Arquitetura
Fonte de verdade do código: GitHub `cremeni25/e-commerce`.
Arquitetura nova: GitHub + Next.js storefront headless + Render + Supabase dedicado `cremeni-commerce`.
Não reutilizar CTI, Robo Global ou Performance Atleta.
Não voltar a WordPress/UOLHost como caminho crítico. Preservar o legado e backup até corte futuro.
Não alterar DNS de cremeni.com.br antes da homologação completa.

## Marco homologado
Main homologado até autenticação/carrinho: `085e697f5bef67b9caf2b2435c7176474860f56c`.
Conta/auth: homologado.
Carrinho: homologado.
Pagamento real: desabilitado.
Estoque real: zero/não homologado.

## Estado reconciliado da branch atual
Branch de trabalho: `commerce-checkout-frete`.
Após o marco acima, a branch recebeu estrutura real de endereço/frete/checkout em homologação, sem liberar operação comercial:
- `customer_addresses` persistente;
- `shipping_quotes` e componentes por origem;
- modelagem de `fulfillment_origins` e vínculo produto→origem;
- readiness de frete exige estoque, dimensões e origem reais;
- Edge Function `shipping-quote` usa Melhor Envio somente quando credencial real existir;
- cotação multi-origem sem valor fictício;
- seleção entre modalidade econômica e mais rápida baseada exclusivamente nas cotações reais retornadas pelo provedor;
- cotação persistida vinculada ao endereço usado na consulta;
- `create_checkout_order` revalida usuário, carrinho, endereço, cotação, preço e estoque;
- pedido, quando permitido, nasce com `payment_status = pending`;
- pagamento/gateway não é acionado.

## Bloqueios reais atuais
- estoque dos produtos SPORT continua 0;
- peso/dimensões ainda não homologados para o catálogo;
- origem logística real ainda não consolidada por produto;
- credencial do provedor logístico ainda não configurada;
- imagens definitivas/autorizadas ainda não materializadas;
- PET MIMOS ainda não materializado como catálogo final;
- Guia CREMENI nº 001 ainda não produzido como artefato final;
- pagamento continua bloqueado;
- DNS/produção continuam intocados.

## Regra de execução
Não inventar estoque, fornecedor, frete, margem, comissão, pedido ou venda. O chat toma decisões técnicas ordinárias e usa conectores. O humano valida negócio, visual, custos, produção, DNS e decisões financeiras relevantes. Ações consequenciais exigem autorização quando aplicável.
