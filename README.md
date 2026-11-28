# Repository Catalog

Plugin WordPress para apresentar repositórios públicos do GitHub, agrupados por tópicos.

O código em desenvolvimento está na branch [dev](https://github.com/humanitastech/wpgithub-basics/tree/dev).

## Funcionalidades

- Cartões com descrição, linguagem, estatísticas e dados de release disponíveis.
- Agrupamento pelos tópicos plugin, skill, theme, app, library e docs.
- Seleção de repositórios, ordenação, forks e arquivados configuráveis.
- Cache, orçamento de chamadas e recuo diante de erros da API.
- Links diretos para os repositórios públicos.

## Instalação

Requer WordPress 6.5 ou superior e PHP 7.4 ou superior.

Na branch dev, execute o empacotamento na raiz do projeto.

~~~powershell
./build-package.ps1
~~~

O resultado é dist/repository-catalog.zip. Instale-o em Plugins → Adicionar plugin → Enviar plugin e configure Integração GitHub basics.

Consulte documentacao/CONFIGURACAO_SEGURA.md para definir a política de consultas, origens HTTPS e credencial opcional do ambiente.

Adicione o shortcode a uma página.

~~~text
[ht_github_projects_basics]
[ht_github_projects_basics include="repositorio-a,repositorio-b"]
~~~

## Desenvolvimento

~~~powershell
php tests/security.php
~~~

O diretório do plugin é codigo/repository-catalog. A configuração usa a opção htgp_basics_settings e as variáveis HTGP_BASICS_*.

A branch main recebe as versões promovidas. O desenvolvimento acontece em dev.

## Licença

GPL-2.0-or-later. Copyright HumanitasTech e contribuidores.

## Nomes de apresentação — candidata 1.1.0

O cartão pode usar display_name de ARTICLE.md da tag da release estável mais recente. O campo deve ser uma string entre aspas no front matter, como `display_name: "Web Security Audit Skill"`. Ele não renomeia repositórios nem cria Posts. Sem release, arquivo ou metadado válido, permanece o nome técnico. O link segue direto para o GitHub.

O parser é restrito e compartilhado por equivalência com a edição completa, com prefixo próprio. Não executa YAML, PHP ou shortcodes, não renderiza o corpo do artigo e armazena em cache somente o nome sanitizado. A consulta pública não envia a credencial de listagem e usa ref explícita refs/tags/<tag>. Há até uma consulta adicional por repositório com release; dimensione a política de chamadas existente sem presumir aumento de limites.

Candidata em dev; publicação de release da basics não incluída nesta entrega. Veja documentacao/VALIDACAO_1.1.0.md.
