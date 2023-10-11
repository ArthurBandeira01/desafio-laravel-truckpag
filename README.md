
# Challenge Truckpag - Laravel

Neste projeto foi feito uma REST API com Laravel para para utilizar os dados do projeto Open Food Facts que tem como objetivo dar suporte a equipe de nutricionistas da empresa Fitness Foods LC para que eles possam revisar de maneira rápida a informação nutricional dos alimentos que os usuários publicam pela aplicação móvel.

### Tecnologias usadas:

* Laravel (v10);
* MySQL;
* PHPUnit;
* Postman.

### Requisitos

* Ter no mínimo a versão 8.1 do PHP instalado;
* PHPUnit versão ^10.0;
* MySQL.

### Intalação

Para instalar a última versão do Laravel com Docker utilizei o Laravel Sail que é ferramenta de linha de comando para interagir com o ambiente de desenvolvimento. Com ele pude iniciar o Laravel com o MySQL, Redis e Mailpit utilizando o seguinte comando:

```curl -s "https://laravel.build/desafio-laravel-truckpag?with=mysql,redis,mailpit" | bash
 sail up -d
```

Para rodar testes unitários da API utilizei o PHPUnit, para instalá-lo rodei o seguinte comando:

`composer require --dev phpunit/phpunit`

A partir da instalação dessas dependências comecei a planejar o modelo do banco no mysql.

OBS.: No meu caso como já tinha o MySQL Workbench instalado na máquina utilizei ele para visualização de interface gráfica.

### Passo a passo da lógica

Após ter instalado o Laravel Sail no Windows com WSL2 e ter iniciado o container (sail up -d) rodei os comando para criar o modelo e a migration do banco:

`php artisan make:model Product -m`
`php artisan make:model ProductImportHistory -m`

Rodei as migrations:

`php artisan migrate`

O 1° passo da integração foi criar a CRON no Laravel que importará e atualizará os dados na base e registrará também um registro/histórico de importação na tabela de apoio criada na migration:

`php artisan make:command ImportProducts --command=import:products`

Para rodar o comando manualmente acima:

`php artisan import:products`

Para agendá-lo e rodar o comando diariamente conforme a descrição do teste há 2 formas que pode ser no arquivo de cron ou direto no cron jobs do cpanel da aplicação, por exemplo:
Minute  Hora    Dia     Month   Weekday  Caminho até o comando
` 00    04      *       *      *         /opt/cpanel/ea-php81/root/usr/bin/php /home/usuario/projeto/artisan import:products`

Logo criei o controller da API Rest:

`php artisan make:controller App\\Product --resource`

Criei o resource da API para mostrar os dados de forma padrão:

`php artisan make:resource ProductResource`

Criei uma seeder para levantar junto com o banco e atualizar no endpoint PUT e uma factory que utilzei nos testes unitários:

```

php artisan make:seeder ProductSeeder
php artisan make:factory ProductFactory

```
Já partindo para a 2° parte do challenge foi feito as rotas e endpoints para a API, no caso foram 5:

* GET '/' ----> Detalhes da API, se conexão leitura e escritura com a base de dados está OK, horário da última vez que o CRON foi executado, tempo online e uso de memória.
* GET '/products' ----> Listar todos os produtos da base de dados, adicionar sistema de paginação para não sobrecarregar o REQUEST
* GET '/products/{code}' ----> Obter a informação somente de um produto da base de dados
* PUT '/products/{code}' ----> Será responsável por receber atualizações do Projeto Web
* DELETE '/products/{code}' ----> Mudar o status do produto para trash

Utilizei o Guzzle para fazer requisições e para baixá-lo rodei o seguinte comando:

`composer require guzzlehttp/guzzle`

Para criar os testes utilizei o comando a seguir seguindo o padrão de estrutura de pastas:

`php artisan make:test app/Http/Controllers/Api/ProductTest`

**NOTAS**: 
* Para rodar o teste pode-se utilizar o seguinte comando com o método de teste -> `php artisan test --filter ProductTest::testIndex` 
* Para rodar o servidor: 
```php artisan serve
    php artisan migrate db:seed
```

Link Collection Postman: <https://api.postman.com/collections/12254422-4f3bd192-6afe-4fe0-8a8e-d21a69f506cd?access_key=PMAT-01HCF2Q343YNY05SQ0CB722MXD>

### Vídeo de apresentação do projeto
Link do vídeo: <https://coodesh.com>

This is a challenge by [Coodesh](https://coodesh.com/)
