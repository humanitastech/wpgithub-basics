# HumanitasTech GitHub for WordPress basics 1.0.0: seus repositórios em um catálogo no site

O HumanitasTech GitHub for WordPress basics é um plugin para quem deseja apresentar seus repositórios públicos do GitHub em uma página WordPress. Ele reúne informações dos projetos em cartões e ajuda o visitante a encontrar uma ferramenta antes de abrir seu repositório.

Depois da instalação e da configuração do perfil GitHub, basta adicionar o shortcode `[ht_github_projects_basics]` a uma página. Os cartões exibem descrição, linguagem, estatísticas e informações da última release, quando disponíveis. Cada cartão oferece um link direto para o repositório.

## O que oferece a versão 1.0.0

Esta é a primeira edição basics, com os recursos necessários para montar um catálogo de repositórios públicos:

- Agrupamento automático pelos tópicos `plugin`, `skill`, `theme`, `app`, `library` e `docs`.
- Seleção de repositórios e configuração da ordem de exibição.
- Opções para incluir ou excluir forks e repositórios arquivados.
- Filtro por página, como `[ht_github_projects_basics include="repositorio-a,repositorio-b"]`.
- Cache das respostas e limites de consultas à API do GitHub, com espera diante de falhas.

Os tópicos são as etiquetas cadastradas no GitHub. Quando existem categorias reconhecidas, os demais repositórios aparecem no grupo Geral. Se nenhum dos repositórios exibidos tiver esses tópicos, o catálogo mantém uma grade sem grupos.

## Requisitos e configuração

O plugin declara suporte a WordPress 6.5 ou superior e PHP 7.4 ou superior. A hospedagem precisa permitir consultas HTTPS à API configurada.

A instalação exige definir no servidor a política de consultas e as origens HTTPS permitidas, conforme o [guia de configuração](https://github.com/humanitastech/wpgithub-basics/blob/dev/documentacao/CONFIGURACAO_SEGURA.md). Uma credencial GitHub é opcional; quando utilizada, fica no ambiente do servidor. Depois, o administrador configura o perfil e os filtros em **Integração GitHub basics**.

## Disponibilidade

Mantido pela HumanitasTech, o plugin está disponível no [repositório público wpgithub-basics](https://github.com/humanitastech/wpgithub-basics), sob a licença GPL-2.0-or-later. O código da versão 1.0.0 está na [branch dev](https://github.com/humanitastech/wpgithub-basics/tree/dev), com instruções para gerar o pacote instalável `htgithubforwp-basics.zip`.
