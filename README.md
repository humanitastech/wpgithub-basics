# HumanitasTech GitHub for WordPress basics

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

O resultado é dist/htgithubforwp-basics.zip. Instale-o em Plugins → Adicionar plugin → Enviar plugin e configure Integração GitHub basics.

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

O diretório do plugin é codigo/htgithubforwp-basics. A configuração usa a opção htgp_basics_settings e as variáveis HTGP_BASICS_*.

A branch main recebe as versões promovidas. O desenvolvimento acontece em dev.

## Licença

GPL-2.0-or-later. Copyright HumanitasTech e contribuidores.
