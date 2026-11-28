# Configuração segura — basics 1.0.0

Nenhum endpoint, origem, token ou limite operacional é presumido pelo runtime. A implantação escolhe os valores em ambiente ou constantes do wp-config.php, fora do Git e do ZIP. Sem política válida, a integração falha de forma fechada. Não copiar credenciais para testes, mensagens ou opções do WordPress.

## Primeira configuração sem token

O exemplo abaixo usa o GitHub público e valores para avaliar o catálogo em um site de teste. Revise os limites conforme a hospedagem e o volume de tráfego antes de usar em produção.

Adicione ao arquivo wp-config.php, antes de ele carregar wp-settings.php, ou defina os mesmos valores em variáveis de ambiente. As variáveis de ambiente têm precedência sobre as constantes.

~~~php
define('HTGP_BASICS_PUBLIC_LINK_ORIGINS', 'https://github.com');
define('HTGP_BASICS_PROFILE_ORIGINS', 'https://github.com');
define('HTGP_BASICS_FAILURE_TTL_SECONDS', 60);
define('HTGP_BASICS_MAX_BACKOFF_SECONDS', 300);
define('HTGP_BASICS_REQUEST_BUDGET_SECONDS', 10);
define('HTGP_BASICS_MAX_REQUESTS', 11);
define('HTGP_BASICS_MAX_TIMEOUT_SECONDS', 3);
define('HTGP_BASICS_MAX_RESPONSE_SIZE', 524288);
define('HTGP_BASICS_MAX_API_PAGE_SIZE', 5);
~~~

Com o plugin ativo, abra **Configurações → Integração GitHub basics** e preencha os campos.

| Campo | Valor de exemplo |
|---|---|
| Endpoint HTTPS da API | `https://api.github.com` |
| Versão da API | `2022-11-28` |
| Identificação HTTP | `htgithubforwp-basics-review` |
| Timeout em segundos | 3 |
| Limite da resposta em bytes | 524288 |
| Cache em segundos | 600 |
| Máximo de projetos por consulta | 5 |
| Limite do identificador da conta | 39 |
| Limite do filtro de inclusão | 512 |
| Limite do nome de repositório | 100 |
| URL do perfil no GitHub | `https://github.com/humanitastech` |
| Quantidade | 5 |
| Ordenação | `full_name` |
| Direção | `asc` |
| Vínculo | `owner` |

Salve as opções e abra uma página com um bloco Shortcode contendo `[ht_github_projects_basics]`. A página deve mostrar os repositórios públicos do perfil, com links para o GitHub. Os cartões mostram dados da última release estável quando disponíveis.

## Parâmetros de conexão e consulta
| Ambiente/constante | Opção administrativa de fallback |
|---|---|
| HTGP_BASICS_GITHUB_API_BASE_URL | api_base_url |
| HTGP_BASICS_GITHUB_API_VERSION | api_version |
| HTGP_BASICS_HTTP_USER_AGENT | user_agent |
| HTGP_BASICS_HTTP_TIMEOUT_SECONDS | timeout_seconds |
| HTGP_BASICS_RESPONSE_SIZE_BYTES | response_size |
| HTGP_BASICS_CACHE_TTL_SECONDS | cache_ttl_seconds |
| HTGP_BASICS_API_PAGE_SIZE | api_page_size |
| HTGP_BASICS_MAX_OWNER_LENGTH | max_owner_length |
| HTGP_BASICS_MAX_INCLUDE_LENGTH | max_include_length |
| HTGP_BASICS_MAX_REPOSITORY_NAME_LENGTH | max_repo_length |

A variável de ambiente tem precedência. A constante é consultada quando a variável não existe. A opção administrativa é usada quando o valor resultante está vazio. O token e a política abaixo não têm fallback no banco. Variáveis numéricas são inteiros positivos em representação decimal canônica.

## Política obrigatória de ambiente
| Nome | Finalidade |
|---|---|
| HTGP_BASICS_PUBLIC_LINK_ORIGINS | Origens HTTPS permitidas nos links públicos, separadas por vírgula |
| HTGP_BASICS_PROFILE_ORIGINS | Origens HTTPS permitidas para o perfil configurado |
| HTGP_BASICS_FAILURE_TTL_SECONDS | Espera mínima após falha externa |
| HTGP_BASICS_MAX_BACKOFF_SECONDS | Teto de espera aplicado a Retry-After |
| HTGP_BASICS_REQUEST_BUDGET_SECONDS | Tempo agregado disponível por cliente no pedido PHP |
| HTGP_BASICS_MAX_REQUESTS | Quantidade máxima de chamadas HTTP por cliente no pedido PHP |
| HTGP_BASICS_MAX_TIMEOUT_SECONDS | Teto do timeout administrativo |
| HTGP_BASICS_MAX_RESPONSE_SIZE | Teto do tamanho de resposta administrativo |
| HTGP_BASICS_MAX_API_PAGE_SIZE | Teto da quantidade de repositórios |

As origens devem ser canônicas: esquema https, host em minúsculas, porta explícita somente se necessária, sem caminho, barra final, query, fragmento ou credenciais. A lista deve conter somente serviços que o operador aprovou. O endpoint pode conter um caminho de API empresarial, mas não query/fragmento. HTTPS público não significa destino autorizado a receber segredo.

HTGP_BASICS_GITHUB_TOKEN é opcional e deve ter o mínimo privilégio. Se usado, HTGP_BASICS_GITHUB_TOKEN_ORIGIN é obrigatório e deve coincidir exatamente com a origem do endpoint. A opção administrativa não pode trocar a origem da credencial. Para dados públicos, preferir operação sem token quando a quota permitir.

O operador escolhe os limites após avaliar capacidade e latência: timeout <= MAX_TIMEOUT_SECONDS; tamanho <= MAX_RESPONSE_SIZE; paginação <= MAX_API_PAGE_SIZE; FAILURE_TTL_SECONDS <= MAX_BACKOFF_SECONDS. Não existem valores operacionais padrão. Retry-After numérico ou data HTTP é respeitado até o teto aprovado. O tempo total é um orçamento das chamadas externas, não garantia do tempo total de CPU/WordPress.

## Instalação e operação

Configure a política de ambiente e preencha o perfil e os filtros em Integração GitHub basics. Valide a consulta normal, o cache, os erros da API e a recusa de alterações administrativas para usuários sem manage_options.

O cache muda de namespace com endpoint, configuração, versão ou contexto da credencial. A credencial é representada somente por HMAC com salt WordPress, nunca por texto em chave/cache. Caches antigos expiram normalmente. Só campos públicos usados no template são persistidos.

A trava compartilhada usa opção não autoload com expiração e exclusão condicional pelo valor completo. Pedidos concorrentes não aguardam: recebem indisponibilidade temporária quando não há cache. Falhas de transporte, status ou esquema acionam espera para o mesmo contexto. A consulta anônima de releases usa um recuo separado quando a listagem utiliza uma credencial. Releases 404 têm cache negativo normal. Planejar limpeza operacional de locks abandonados de contextos aposentados; a própria consulta recupera locks vencidos no contexto atual.

O plugin emite htgp_basics_security_event com códigos sanitizados para falhas, rejeição de linhas e validação de configuração. Um consumidor operacional ainda deve registrar data, contexto, resultado e retenção apropriados; o hook não constitui sozinho uma trilha persistente.

A apresentação continua com HTML escapado e CSS do plugin. Não existe formulário público, upload ou escrita no GitHub.
