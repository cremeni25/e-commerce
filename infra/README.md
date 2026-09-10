# CREMENI — infraestrutura WordPress operável

Objetivo: retirar a operação diária da dependência do WP Toolkit/cPanel sem alterar `cremeni.com.br` antes de uma homologação completa.

## Princípios

- GitHub continua como fonte de verdade do código CREMENI.
- WordPress roda em ambiente isolado, com volumes persistentes para banco, plugins e uploads.
- Tema `cremeni-store` e MU plugins vêm do repositório e são montados como somente leitura.
- MariaDB é separado do container WordPress.
- Caddy provê HTTPS automático no ambiente homologado.
- Nenhum segredo é versionado.
- DNS de produção só muda depois de homologação funcional, visual e comercial.

## Sequência operacional

1. Provisionar servidor de homologação.
2. Criar `.env` a partir de `.env.example` com segredos fora do Git.
3. Subir a stack com `docker compose up -d`.
4. Importar cópia do banco e uploads da hospedagem atual.
5. Reinstalar WooCommerce oficial e apenas os plugins realmente necessários.
6. Validar login, checkout, catálogo, mídia, URLs, mobile, e regras comerciais CREMENI.
7. Validar `cremeni.com.br` em homologação antes da troca de DNS.
8. Manter UOLHost intacta até o aceite final.

## Observação crítica sobre a hospedagem atual

O repositório atual possui automação de deploy para `/home/cremenib3a76cde/public_html`, enquanto o WP Toolkit visto durante a recuperação apontou uma instalação em `/home/cremenib3a76cde/public_html/novo`. Essa divergência precisa ser tratada como risco de ambiente duplicado/registro obsoleto; nenhuma alteração de produção deve assumir que ambos representam a mesma instalação.
