# Senior

**API PHP para integração com o [Sistema Aturial Senior](https://documentacao.senior.com.br//)**

*Senior* é um framework em PHP, que permite a integração de um aplicativo com os
serviços dos webservices providos pelo Sistema Senior, realizando a montagem
das mensagens SOAP.

[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

[![Issues][ico-issues]][link-issues]
[![Forks][ico-forks]][link-forks]
[![Stars][ico-stars]][link-stars]

## CONTEXTO


## OBJETIVO


## INSTALAÇÃO

A API pode ser instalada por linha de comando com o composer

```
composer require nfephp-org/senior
```

Ou colocando como dependência diretamente no composer.json do seu aplicativo:

```json
    "require": {
        "nfephp-org/senior" : "^0.1"
    },
```

## DEPENDÊNCIAS

- PHP >= 7.2 (preferencialmente 7.3 ou maior)
- *php-curl* CURL module for PHP
- *php-xml* DOM, SimpleXML, WDDX, XML, and XSL module for PHP
- *php-json* JSON module for PHP
- *php-zlib* CORE DEFAULT
- *php-date* CORE DEFAULT

## USO

Para usar você pode ver os exemplos na pasta exemples.



## CREDITOS

- Rodrigo Traleski <rodrigo@actuary.com.br>
- Luiz Eduardo Godoy Bueno <luizeduardogodoy@gmail.com>
- Roberto L. Machado <linux.rlm@gmail.com>

O desenvolvimento desse pacote somente foi possivel devido a contribuição e colaboração da
[ACTUARY Ltda](http://www.actuary.com.br/v2/informatica/index.php)

## LICENÇA

Este patote está diponibilizado sob LGPLv3, GPLv3 ou MIT License (MIT). Leia  [Arquivo de Licença](LICENSE.md) para maiores informações.

[ico-stars]: https://img.shields.io/github/stars/nfephp-org/senior.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/nfephp-org/senior.svg?style=flat-square
[ico-issues]: https://img.shields.io/github/issues/nfephp-org/senior.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nfephp-org/Senior/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nfephp-org/senior.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nfephp-org/senior.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/senior.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/senior.svg?style=flat-square
[ico-license]: https://poser.pugx.org/nfephp-org/senior/license.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/nfephp-org/senior
[link-travis]: https://travis-ci.org/nfephp-org/senior
[link-scrutinizer]: https://scrutinizer-ci.com/g/nfephp-org/senior/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nfephp-org/senior
[link-downloads]: https://packagist.org/packages/nfephp-org/senior
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/senior/issues
[link-forks]: https://github.com/nfephp-org/senior/network
[link-stars]: https://github.com/nfephp-org/senior/stargazers
