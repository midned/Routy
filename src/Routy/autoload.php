<?php

spl_autoload_register(function($class)
{
	// If the class to load isn't from the
	// Routy library do nothing
	if (strpos($class, 'Routy') !== 0)
	{
		return;
	}
	
	// solve the location of the php file
	$file = dirname(__DIR__). DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	
	return ( ! is_file($file)) ?: require $file;
});