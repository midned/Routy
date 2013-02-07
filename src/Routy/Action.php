<?php

namespace Routy;

/**
 * Class representing an action
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @package Routy
 * @license MIT License
 * @copyright 2012 Alvaro Carneiro
 */
class Action {
	
	/**
	 * Routes this callback with handle
	 *
	 * @var string|array
	 */
	protected $route;
	
	/**
	 * The name used to identify this action
	 *
	 * @var string
	 */
	protected $name;
	
	/**
	 * The callback to execute
	 *
	 * @var callable
	 */
	protected $callback;
	
	/**
	 * Condition to execute the action
	 *
	 * @var callable
	 */
	protected $condition;
	
	/**
	 * Callback to execute before the action
	 *
	 * @var callable
	 */
	protected $before;
	
	/**
	 * Callback to execute after the action
	 *
	 * @var callable
	 */
	protected $after;
	
	/**
	 * The router used to generate this action, we need it in some methods
	 *
	 * @var Router
	 */
	private $router;
	
	/**
	 * Constructs a new action
	 *
	 * @param string|array $route Routes this action with handle
	 * @param callable $callback The callback
	 * @param Router $router The router used to generate this action
	 *
	 * @throws InvalidArgumentException When the callback parameter isn't valid
	 */
	public function __construct($route, $callback, Router $router)
	{
		$this->router = $router;
		
		// With this, we're able to register a callback to
		// more routes like this: "/, home, index"
		if (is_string($route)) $route = explode(', ', $route);
		
		// make the $route variable an array so we can attach
		// the callback to more than one route
		$this->route = (array)$route;
		
		$this->callback = $callback;
	}
	
	/**
	 * Get all the routes this action handles
	 *
	 * @return array List of routes
	 */
	public function route()
	{
		return $this->route;
	}
	
	/**
	 * Call the action and pass arguments to it
	 *
	 * @return mixed The returned value of the action
	 */
	public function call($arguments = array())
	{
		$arguments = (array)$arguments;
		
		// for now we can call the action
		$result = true;
		
		// if we defined a condition using the "when" method
		if ($this->condition)
		{
			$result = (bool)call_user_func_array($this->condition, $arguments);
		}
		
		// If the condition returns false
		// throw http not found error
		if ( ! $result)
		{
			throw new HttpException('Route not found', 404);
		}
		
		// if we defined a callback to execute
		// before the action
		if ($this->before)
		{
			// call it passing the same arguments
			// we're going to pass to the action
			$contents = call_user_func_array($this->before, $arguments);
			
			// if it returned something use it as the contents
			// to send (it's like using route filters)
			if ($contents)
			{
				return $contents
			}
		}
		
		// Fetch the contents of the action callback
		// so if we defined an "after" method we'll pass them to it
		$contents = call_user_func_array($this->callback, $arguments);
		
		if ($this->after)
		{
			call_user_func($this->after, $contents);
		}
	}
	
	/**
	 * Sets an identifier to this route, so we make a url to it
	 *
	 * @param string $name The name of this action
	 *
	 * @return Action
	 */
	public function named($name)
	{
		$this->router->identify($this, $this->name = $name);
		
		return $this;
	}
	
	/**
	 * Callback to execute before the action
	 *
	 * @param callable $callback The callback
	 */
	public function before($callback)
	{
		// If the callback is invalid
		// throw an exception
		if ( ! is_callable($callback))
		{
			throw new \InvalidArgumentException('The $callback parameter isn\'t valid.');
		}
		
		$this->before = $callback;
		
		return $this;
	}
	
	/**
	 * Callback to execute after the action
	 *
	 * @param callable $callback The callback
	 */
	public function after($callback)
	{
		// If the callback is invalid
		// throw an exception
		if ( ! is_callable($callback))
		{
			throw new \InvalidArgumentException('The $callback parameter isn\'t valid.');
		}
		
		$this->after = $callback;
		
		return $this;
	}
	
	/**
	 * Set a required condition to execute the action
	 *
	 * <code>
	 *	
	 *	$router->get('greet/:name', function($name)
	 *	{
	 *		echo "Hello {$name}";	
	 *	})->when(function($name)
	 *	{
	 *		return $name == 'Alvaro';
	 *	});
	 *	// the action will be called only when $name equals "Alvaro"
	 * </code>
	 *
	 * @param callable $callback The callback that will return true or false
	 */
	public function when($callback)
	{
		// If the callback is invalid
		// throw an exception
		if ( ! is_callable($callback))
		{
			throw new \InvalidArgumentException('The $callback parameter isn\'t valid.');
		}
		
		$this->condition = $callback;
		
		return $this;
	}
}
