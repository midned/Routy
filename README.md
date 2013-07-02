Routy
=======================

Yet another routing library for php. Very simple `REST` control

## License

MIT License

## Installation

It's `PSR-0` compatible.

You can `git clone` it and include `src/Routy/autoload.php` to autoload the classes.

And you can install it via [composer](https://packagist.org/packages/routy/routy)

## Documentation

### First step


```php

<?php

require_once 'src/Routy/autoload.php';

// if installed with composer include
// require_once 'vendor/autoload.php';

use Routy\Router;

```

### Creating the router instance

Simple as the following lines:


```php

<?php

$app = new Router();

```

### Generating the base url

The first parameter in the constructor of the `Routy\Router` class is the `base url`. If it's null or not defined it will be generated.

The second parameter is a boolean. In case we want to include the script name in the url we will set it to true. We will set to false otherwise (and it's false by default)

The `base url` will be needed in the future if you want to generate absolute urls of your defined routes
Examples:

```php

<?php

$router = new Router();

$router->base(); // will return something like http://site.com/

$router = new Routy\Router(null, true);

$router->base(); // will return something like http://site.com/index.php


$router = new Routy\Router('http://some.net');

$router->base(); // will return http://some.net


```

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

### Throwing http errors

Sometimes you want to throw a http error for some reason like a record in the database that was not found or something like that, to do that exists the `Router::produce` method.
Example:

```php
$router->get('user/{num}', function($id) use(&$router) // use the router global variable
{
	// some code to fetch the user
	
	if (!$user) // if the user doesn't exist in the database
	{
		$router->produce(404);
	}

	// your code
});
``` 

### Error handler

When the current URL doesn't correspond to any defined route we'll throw a `Routy\HttpException`. With the code of the error (404).

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

You can hide the script name in the uri file using, for example, a `.htaccess` (like the example one on this repo)

### Still working on the documentation....

