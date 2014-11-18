<?php

/**
* ClassName
*
* @author   Fabian Siatama
* 
* Se definen varias utilidades comunes a toda la aplicacion
*/
class Helpers
{
	public static function getDateTimeNow()
	{
		return date("Y-m-d H:i:s");
	}
	
	public static function getDateNow()
	{
		return date("Y-m-d");
	}

	public static function arrayGet($array, $key)
	{
		if (is_null($key)) return $array;

		if (isset($array[$key])) return $array[$key];

		foreach (explode('.', $key) as $segment)
		{
			if ( ! is_array($array) || ! array_key_exists($segment, $array))
			{
				return '';
			}

			$array = $array[$segment];
		}

		return $array;
	}

	public static function getRequire($path)
	{
		if (file_exists($path)) {
			return require $path;
		}
		exit("File does not exist at path {$path}");
	}
		
}
