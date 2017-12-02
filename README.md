# users

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads](https://poser.pugx.org/acacha/users/downloads.png)](https://packagist.org/packages/acacha/users)
[![Monthly Downloads](https://poser.pugx.org/acacha/users/d/monthly)](https://packagist.org/packages/acacha/users)
[![Daily Downloads](https://poser.pugx.org/acacha/users/d/daily)](https://packagist.org/packages/acacha/users)
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![StyleCI](https://styleci.io/repos/35628567/shield)](https://styleci.io/repos/35628567)
[![Code Coverage](https://scrutinizer-ci.com/g/acacha/users/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/acacha/users/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/593803c298442b00398eb8eb/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/593803c298442b00398eb8eb)[![Dependency Status Node.js](https://www.versioneye.com/user/projects/58483fc88c5dae0039a10ca5/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/58483fc88c5dae0039a10ca5)

Acacha users is a Laravel package that add Users managment support yo your Laravel app. 

## Installation

Via Composer please first create a new fresh Laravel Project:

``` bash
laravel new laravel_with_users
cd laravel_with_users
```

Then install this Laravel Package using:

``` bash
composer require acacha/users
```

## Usage

``` php
$skeleton = new Acacha\Users();
echo $skeleton->echoPhrase('Hello, League!');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email sergiturbadenas@gmail.com instead of using the issue tracker.

## Credits

- [Sergi Tur Badenas][link-author]
- [All Contributors][link-contributors]

# TODO Javascript/Npm dependencies

- adminlte-vue
- password-generator https://www.npmjs.com/package/password-generator

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/acacha/users.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/acacha/users/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/acacha/users.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/acacha/users.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/acacha/users.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/acacha/users
[link-travis]: https://travis-ci.org/acacha/users
[link-scrutinizer]: https://scrutinizer-ci.com/g/acacha/users/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/acacha/users
[link-downloads]: https://packagist.org/packages/acacha/users
[link-author]: https://github.com/acacha
[link-contributors]: ../../contributors
