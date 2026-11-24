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

1. Gere htgithubforwp-basics.zip com build-package.ps1.
2. Instale o ZIP em Plugins → Adicionar plugin → Enviar plugin.
3. Siga documentacao/CONFIGURACAO_SEGURA.md para os parâmetros de ambiente.
4. Configure Integração GitHub basics e adicione o shortcode a uma página.

== External services ==

O plugin consulta a API GitHub na origem HTTPS aprovada pelo ambiente. A organização que o instala deve informar o serviço e os termos aplicáveis aos seus usuários.

== Changelog ==

= 1.1.0 =
* Nome de apresentação display_name lido de ARTICLE.md da tag da release estável.
* Fallback técnico para metadados ausentes ou inválidos; links continuam diretos ao GitHub.
* Parser restrito e cache apenas do nome, sem importação do corpo ou criação de Posts.

= 1.0.0 =
* Primeira edição basics com repositórios públicos, agrupamento por tópicos, cache e filtros.
