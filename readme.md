# :package_name

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]


Build your Laravel application from the command line or, at some point in the future, through an interface.

## Install

Via Composer

``` bash
$ composer require jeroen-g/laravel-builder
```

In config/app.php add

``` php
        JeroenG\LaravelBuilder\LaravelBuilderServiceProvider::class,
```

## Usage

### In your code

An instance of the builder class is binded in the service provider. You can use the Builder in your code in one of the following ways.
Stubs are searched in the `stubs/` directory, so run the command first (see next section).

1. With variables
``` php
$builder->stub = 'model';  // model.stub found in stubs/
$builder->namespace = 'App\\Models';
$builder->class = 'Test';
$builder->path = 'app/Models/Test';  // Relative to base_path().
$builder->run()->save();  // Creates and then saves the file.
$builder->reset();  // Resets the variables for a completely new build process.
```

2. With an array
``` php
$builder->fromArray([
    'stub' => 'model',
    'namespace' => 'App\\Models',
    'class' => 'Test',
    'path' => 'app/Models/Test'
]);  // No need to run() or save() or reset().
```

3. With Json
``` php
$json = json_encode([  // You could for example have .json files for this.
    'stub' => 'model',
    'namespace' => 'App\\Models',
    'class' => 'Test',
    'path' => 'app/Models/Test'
]);
$builder->fromJson($json);  // No need to run() or save() or reset().
```

### The command line

You can also use the following commands.

1. Publish all the included stubs to the `stubs/` directory.
``` bash
$ php artisan build:stubs
```
You can add your own stubs to this directory as well.
It currently ships with very little stubs, if you have made your own, feel free to add them to the package with a Pull Request.

2. Building a file
``` bash
$ php artisan build model App\\Models Test app/Models/Test
```

### Through the interface

Not present at the moment, if you want to help with making this UI, please contact me!

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jeroen-g/laravel-builder.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/:vendor/:package_name/master.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jeroen-g/laravel-builder
[link-travis]: https://travis-ci.org/:vendor/:package_name
[link-contributors]: ../../contributors
