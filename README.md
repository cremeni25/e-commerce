# Cremeni E-commerce

Repositório oficial da transformação do site `www.cremeni.com.br` em uma loja virtual profissional sobre WordPress e WooCommerce, mantendo domínio, hospedagem UOL Host cPanel e banco de dados atuais.

## Arquitetura definida

- WordPress
- WooCommerce
- Tema customizado `cremeni-store`
- GitHub como fonte de verdade do código
- Git Version Control do cPanel para clonagem, pull e deploy
- SSH/Jailed Shell para operação do repositório no servidor

## Estrutura versionada

```text
wp-content/
  themes/
    cremeni-store/
  mu-plugins/
  plugins-custom/
deploy/
docs/
```

## Regras do repositório

- Não versionar o núcleo do WordPress.
- Não versionar uploads, cache, backups, logs ou arquivos com credenciais.
- Plugins de terceiros serão instalados e atualizados pelo WordPress/WooCommerce.
- Código próprio ficará em `wp-content/themes/cremeni-store`, `wp-content/mu-plugins` e `wp-content/plugins-custom`.
- O deploy para produção será habilitado após a liberação do Jailed Shell/SSH pela UOL Host.

## Status

- Repositório GitHub criado.
- Chave SSH do cPanel autorizada.
- Clone do GitHub no cPanel realizado.
- Chamado UOL Host aberto para habilitação do Jailed Shell/SSH.
