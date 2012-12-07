<?php

namespace Routy;

/**
 * Represents an Http error to the Routy library
 *
 * @author Alvaro Carneiro <d3sign.night@gmail.com>
 * @package Routy
 * @license MIT License
 * @copyright 2012 Alvaro Carneiro
 */
class HttpException extends \Exception {
	
	public function __construct($message = null, $code = 500)
	{
		parent::__construct($message, $code);
	}
	
}