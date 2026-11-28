=== HumanitasTech GitHub for WordPress basics ===
Contributors: humanitastech
Tags: github, repositories, portfolio, shortcode
Requires at least: 6.5
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Catálogo de repositórios públicos do GitHub agrupados por tópicos.

== Description ==

Configure o perfil GitHub e insira [ht_github_projects_basics] em uma página.
Os cartões apresentam descrição, linguagem, datas e estatísticas disponíveis e levam ao repositório.
Os tópicos plugin, skill, theme, app, library e docs organizam o catálogo. Outros repositórios ficam em Geral quando há grupos reconhecidos.

== Installation ==

1. Instale e ative o plugin no painel do WordPress. Se recebeu um ZIP, use Plugins → Adicionar plugin → Enviar plugin.
2. Configure no servidor as origens HTTPS e os limites de consulta obrigatórios. O [guia de configuração com exemplo sem token](https://github.com/humanitastech/wpgithub-basics/blob/dev/documentacao/CONFIGURACAO_SEGURA.md#primeira-configuração-sem-token) mostra quais constantes adicionar ao wp-config.php e quais valores preencher no painel. Você precisa ter acesso à configuração do servidor e a hospedagem precisa permitir consultas HTTPS à API.
3. Em Configurações → Integração GitHub basics, preencha os dados da API e a URL do perfil público, escolha a quantidade e a ordenação dos repositórios e salve.
4. Insira [ht_github_projects_basics] em um bloco Shortcode e visualize a página para conferir os cartões.

== External services ==

O plugin usa a [API REST do GitHub](https://docs.github.com/en/rest) para montar o catálogo de repositórios públicos. Depois que o administrador configura a integração e adiciona o shortcode, o servidor WordPress consulta o endpoint HTTPS configurado quando a página é renderizada e o cache precisa ser preenchido ou renovado.

O servidor envia o identificador do perfil, os parâmetros de paginação, vínculo e ordenação, a versão da API e a identificação HTTP configurada. Quando consulta a última release estável e o nome de apresentação em ARTICLE.md, também envia os identificadores do proprietário e do repositório e, para o arquivo, a referência da tag. Se houver um token opcional, ele é enviado no cabeçalho de autorização somente na listagem de repositórios e somente à origem autorizada pelo administrador. As consultas de release e de ARTICLE.md são anônimas.

O WordPress guarda temporariamente os metadados públicos necessários aos cartões, como nomes, descrições, linguagens, estatísticas, tópicos, links e dados da release. De ARTICLE.md, o cache guarda apenas o nome de apresentação validado.

* [Termos de uso do GitHub](https://docs.github.com/en/site-policy/github-terms/github-terms-of-service)
* [Política de privacidade do GitHub](https://docs.github.com/en/site-policy/privacy-policies/github-general-privacy-statement)

== Changelog ==

= 1.1.0 =
* Nome de apresentação display_name lido de ARTICLE.md da tag da release estável.
* Fallback técnico para metadados ausentes ou inválidos; links continuam diretos ao GitHub.
* Parser restrito e cache apenas do nome, sem importação do corpo ou criação de Posts.

= 1.0.0 =
* Primeira edição basics com repositórios públicos, agrupamento por tópicos, cache e filtros.
