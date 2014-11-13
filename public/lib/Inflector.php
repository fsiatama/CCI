<?php

class Inflector {

	public static function camel($value)
	{
		$segments = explode('-', $value);

		array_walk($segments, function (&$item) {
			$item = ucfirst($item);
		});

		return implode('', $segments);
	}

	public static function lowerCamel($value)
	{
		return lcfirst(static::camel($value));
	}

	public static function underscore($value)
	{
		$segments = explode(' ', $value);

		array_walk($segments, function (&$item) {
			$item = strtolower($item);
		});

		return implode('_', $segments);
	}

	public static function slug($value)
	{
		$segments = explode(' ', $value);

		array_walk($segments, function (&$item) {
			$item = strtolower($item);
		});

		return implode('-', $segments);
	}

}