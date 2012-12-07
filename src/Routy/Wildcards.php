<?php

namespace Routy;

/**
 * Class to list the route wildcards, apply them and create new
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @package Routy
 * @license MIT License
 * @copyright 2012 Alvaro Carneiro
 */
class Wildcards {
	
	/**
	 * The wildcard patterns we replace in the routes
	 *
	 * @var array
	 */
	protected static $list = array(
		'{any}'     => '(.+)',
		'{alnum}'   => '([[:alnum:]]+)',
		'{num}'	    => '([[:digit:]]+)',
		'{alpha}'   => '([[:alpha:]]+)',
		'{segment}' => '([^/]*)',
	);
	
	/**
	 * Create a new wildcard
	 *
	 * @param string $name The name of the wildcard, without "{}"
	 * @param string $replacement The replacement of the wildcard
	 */
	public static function extend($name, $replacement)
	{
		// if as replace we put the name of
		// another wildcard just use the name
		// as an alias to that one
		if (isset(static::$list[$replacement]))
		{
			$replacement = static::$list[$replacement];
		}
		
		static::$list['{'.$name.'}'] = $replacement;
	}
	
	/**
	 * Apply all the custom wildcards and replace inexistent ones
	 *
	 * @param string $route The route to be replaced
	 *
	 * @return string The resultant
	 */
	public static function make($route)
	{
		$route = static::apply($route);
		
		$route = static::inexistents($route);
		
		return $route;
	}
	
	/**
	 * Apply all the custom wildcards
	 *
	 * @param string $route The route to be replaced
	 *
	 * @return string The resultant
	 */
	public static function apply($route)
	{
		$wildcards = static::$list;
		
		// replace the wildcards with the regular expresions
		return str_replace(array_keys($wildcards), array_values($wildcards), $route);
	}
	
	/**
	 * Replace the inexistent wildcards
	 *
	 * @param string $route The route to be replaced
	 * @param string $to The replacement, by default we use the {alnum} wildcard
	 *
	 * @return string The resultant
	 */
	public static function inexistents($route, $to = '{alnum}')
	{
		return preg_replace('(\{[[:alpha:]]+\})', static::$list[$to], $route);
	}
}