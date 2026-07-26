# Pagamentos, antifraude e chargeback

## Diretriz obrigatória

A Cremeni Store não armazenará número completo de cartão, código de segurança ou qualquer credencial financeira sensível no WordPress. O processamento deverá ocorrer em gateway compatível com tokenização e requisitos PCI DSS.

## Meios previstos

- PIX;
- cartão de crédito;
- boleto bancário;
- parcelamento conforme regra comercial;
- futura recorrência para itens de recompra e serviços digitais.

## Estados mínimos de pagamento

1. aguardando pagamento;
2. pagamento em análise;
3. pagamento aprovado;
4. pagamento recusado;
5. pagamento cancelado;
6. reembolso parcial;
7. reembolso integral;
8. chargeback em disputa;
9. chargeback perdido;
10. chargeback revertido.

## Regras técnicas

- confirmação somente por webhook autenticado;
- validação de assinatura do webhook;
- idempotência para impedir duplicidade de cobrança ou atualização;
- nenhum pedido deve ser enviado ao fornecedor antes da confirmação definitiva;
- logs sem dados completos de cartão ou documentos;
- conciliação diária entre pedidos e transações;
- bloqueio de tentativas repetidas e comportamento compatível com card testing;
- registro do identificador externo da transação no pedido;
- segregação entre ambiente de testes e produção.

## Antifraude

A integração deverá considerar, conforme o provedor escolhido:

- análise de endereço, dispositivo, IP e comportamento;
- divergência entre comprador, titular e destinatário;
- múltiplas tentativas em sequência;
- valor e frequência fora do padrão;
- listas de bloqueio;
- autenticação adicional quando aplicável;
- revisão manual de pedidos de alto risco.

## Chargeback e reembolso

A operação deve manter evidências organizadas:

- aceite dos termos;
- IP e data do pedido;
- comprovante de pagamento;
- nota fiscal;
- rastreio e prova de entrega;
- comunicação com o cliente;
- política de troca e devolução vigente no momento da compra;
- confirmação do fornecedor sobre separação e despacho.

## Dependências futuras

A ativação real depende de:

- escolha do gateway;
- contratação comercial;
- credenciais de produção;
- HTTPS válido;
- WooCommerce operacional no servidor;
- testes de webhook, estorno, cancelamento e conciliação.
