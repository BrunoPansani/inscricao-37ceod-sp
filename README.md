# Inscrições 37º CEOD SP
###### Sistema de Inscrições para o 37º Congresso Estadual da Ordem DeMolay Paulista

Sistema desenvolvido por mim para o 37º CEOD SP realizado nos dias 19 e 20 de Novembro de 2016 na cidade de São Paulo.

Algumas das features presentes são:

1. [Superlógica](http://superlogica.com/developers/api/) como API de Pagamento por restrição da entidade.
2. [API do SISDM-SCODB](http://demolay.org.br) como repositório de dados dos Inscritos.
3. Cadastro de Cartão de Crédito em fluxo através da Superlogica.
4. Possibilidade de emissão de nova cobrança após vencimento.
5. Envio de confirmação individual para os inscritos e listagem das confirmações de pagamento para os organizadores.
6. "Membros Externos": preenchimentos manual dos dados, adicionado após a primeira versão pensada em uma inscrições feitas somente através da API do SISDM.

Toda a função de listagem das inscrições ficou à cargo de uma planilha do Google Spreadsheets e um Google App Script que rodava em intervalos definidos por mim. Hoje, analisando o que foi feito, vejo algumas melhorias claras que poderiam ter sido adotadas, como por exemplo um sistema de template propriamente dito ao invés das horríveis _include's_ e _require's_, utilização de uma API para acesso ao banco de dados e uma modelagem melhor dos dados no banco, entre outras.

**Sinta-se à vontade para sugerir qualquer alteração/correção ou mesmo enviar sua versão do código.**

P.S.: O script foi originalmente pensado para rodar em Linux + PHP, porém a instituição tem um servidor Windows,  por isso os ```web.config``` em coexistência com os ```.htaccess```.
