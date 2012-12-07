Routy
=======================

Yet another routing library for php. Very simple `REST` control

## License

MIT License

## Installation

It's `PSR-0` compatible.

You can `git clone` it and include `src/Routy/autoload.php` to autoload the classes.

And you can install it via [composer](https://packagist.org/packages/Th3-Night/Routy)

## Documentation

### First step


```php

<?php

// If not installed via composer use

// the default autoloader

require_once 'src/Routy/autoload.php';

use Routy\Router;

```

### Create the router

```php

<?php

$app = new Router($context = 'app');

```

Will create an application router to `http://site.com/app`

```php

<?php

$admin = new Router($context = 'app/admin/index.php');

```

Will create an application router to `http://site.com/pap/admin/index.php`


### Handle requests

```php

<?php

$router->any('/, home', function()
{

});

```

That will handle request from any request method to `http://site.com/app_context/` or `http://site.com/app_context/home`

```php

<?php

$router->get('some', function()
{

});

$router->post('some', function()
{

});

```

They will handle get and post requests to `http://site.com/app_context/some`

### RESTful

You can handle `PUT` and `DELETE` requests. But you have to include a hidden field in your html form, something like `<input type="hidden" name="_method" value="put" />` (the same with `DELETE` method).

Example how to handle `PUT` and `DELETE` requests to `http//site.com/app_context/articles`:

```php

<?php

$router->put('articles', function()
{

});

$router->delete('articles', function()
{

});

```

### Route wildcards

Wildcards can be used to pass arguments to the function using the URL

Example:

```php

<?php

$router->get('articles/{id}', function($id)
{
echo "Article {$id}";
});

```

When you go to `http://site.com/app_context/articles/1` you will see "Article 1". But, you also can make the url like `http://site.com/app_context/articles/test` and you will see "Article test", that's a problem.

But, there's a list of default wildcards to use and validate them.

- `{any}`
	- Will match any character. 
	- Regex: `(.+)`
- `{alnum}`
	- Will match alphabetic characters and numbers.
	- Regex: `([[:alnum:]]+)`
- `{num}`
	- Will match any number.
	- Regex: `([[:digit:]]+)`
- `{alpha}`
	- Will match alphabetic characters. 
	- Regex: `([[:alpha:]]+)`
- `{segment}`
	- Will match uri segments (everything except `/`).
	- Regex: `([^/]*)`

Then you want a valid id for the "article" your code must be something like:

```php

<?php

$router->get('articles/{num}', function($id)
{
echo "Article {$id}";
});

```
.

If you preffer to use the custom name of parameter you can extend the wildcards.

```php

<?php

use Routy\Wildcards;

Wildcards::extend('id', '{num}'); // This way you will have an alias to the {num} wildcard

Wildcards::extend('some', '(...)'); // And this way you will have a {some} wildcard with the "(...)" regular expression

```

### Before & After filters

You can execute a callback before or after the main action.

`Before` filter:

```php

<?php

$router->get('some/{num}', function($id)
{

})
->before(function($id)
{

});

```

The `before` filter will receives the same parameters as the main action do

`After` filter:

```php

<?php

$router->get('some/{num}', function($id)
{
return "Selected id {$id}";
})
->after(function($response)
{

});

```

The `after` filter will receive the returned value of the main action. In this case it will receive "Selected id 1" if we go to `http://site.com/app_context/some/1`.

### `When` filter

The when filter is executed before the main action, if it returns true we'll continue the execution, otherwise we'll stop.

Example:

```php

<?php

$router->get('when/{num}', function($num)
{
echo "Selected number: {$num}";
})
->when(function($num)
{
return $num == 2;
});

```

Then if you go to `http://site.com/app_context/2` you will receive an http not found error (later we'll handle them) and you will see "Selected number: (selected one)" when the number isn't `2`.

### Error handler

When the current URL doesn't correspond to any defined route in the .php file we'll throw an `Routy\HttpException`. With the code of the error.

Examples:

```php

<?php

$router->error($error_code = 404, function($error) // the $error variable is the thrown exception

{

});

```

With this you will handle all http errors with the 404 error code.

If you want to handle all type of http errors you can ommit the first parameter.

```php

<?php

$router->error(function()
{

});


```

If we already defined a handler for an http error type the second example will handle all type of errors except the defined one.

### The `.htaccess` file

You can use the router to handle requests to the `app/context/index.php` file, the route will be passed like `index.php/user/1`.

You can hide the `index.php` file using `.htaccess` (there's an example one on the repo)

### Still working on the documentation....