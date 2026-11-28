# Basics1.1.0 — nomes de apresentação

24/11/2026. Alteração aplicada depois da publicação1.2.4 da edição completa, conforme pedido. Candidata de desenvolvimento; a publicação de release da basics não foi incluída.

## Comportamento

DN-B01: cartão usa display_name de ARTICLE.md da tag da latest release estável. Sem campo, arquivo ou release, mantém nome técnico. Não há fallback de branch. DN-B02: link continua direto ao GitHub, sem criar Posts nem publicar corpo. SEC-B01: parser escalar equivalente ao da edição completa, prefixo próprio, HTML/shortcodes/YAML-objects recusados. Cache guarda somente o nome sanitizado; transporte público sem credencial e sem redirects.

## Verificações

72 asserções offline passaram. 15 checks nativos passaram com WordPress6.5, PHP8.2.35 e MariaDB10.4.32 isolados: instalação1.0.0, atualização do ZIP para1.1.0, opções preservadas, shortcode, nomes, tag explícita, ausência de credencial, ausência de Posts, cache, payload hostil, release ausente e reativação. GitHub foi simulado. O core emitiu aviso de conexão externa bloqueada ao WordPress.org; logs preservados e asserções do plugin passaram.

Sintaxe PHP e git diff --check sem erros. ZIP comparado byte a byte com os 8 arquivos da fonte; SHA256 fa3ac696cc591accfb6b7d7cdd5627288387e07807bc8b70959c2692ad905194. Artigo em inglês com nome confirmado Github for Wordpress - Basics.

## Limites

Sem navegador, produção, PHP7.4, multisite ou teste de carga. Uma consulta adicional por repositório com release consome o orçamento compartilhado; não se aumentam limites operacionais automaticamente. Repositórios privados continuam excluídos. A edição basics continua sem importação de Posts. Evidências audit-results/display-name-1.1.0.
