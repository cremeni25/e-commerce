# EXECUÇÃO 019 — Diagnóstico de runtime

Objetivo: identificar por que a Loja CREMENI continua exibindo 0 itens após o reconciliador de catálogo ter sido mergeado.

Endpoint técnico temporário:

`/?cremeni_health=1`

Retorna apenas informações não sensíveis:
- WooCommerce carregado;
- post type `product` registrado;
- taxonomia `product_cat` registrada;
- catálogo piloto carregado;
- reconciliador carregado;
- quantidade de produtos publicados;
- última execução registrada do reconciliador.

Não expõe caminhos internos, credenciais, versões de servidor ou stack trace.
