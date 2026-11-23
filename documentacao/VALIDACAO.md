# Validação da edição basics 1.0.0

Foram aprovadas 60 asserções offline sobre consulta de repositórios públicos, cache, concorrência simulada, limites, segurança HTTP, escape de saída e agrupamento por tópicos.

Os quatro arquivos PHP de produção e os dois arquivos de teste passaram na verificação de sintaxe. O ZIP foi comparado byte a byte à fonte e contém a pasta htgithubforwp-basics com sete arquivos.

A instalação em WordPress com banco real permanece pendente para esta edição. O teste está disponível em tests/integration.php para uma instalação descartável e exige HTGP_BASICS_TEST_ROOT, HTGP_BASICS_TEST_ZIP e o marcador .htgp-disposable na raiz de teste.

O código de produção publica o catálogo de repositórios públicos e seus links diretos. A edição basics usa shortcode, opções e prefixos próprios.

O desenvolvimento é publicado em dev. A branch main contém a apresentação inicial do repositório.