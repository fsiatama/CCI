<?php

/**
* ClassName
*
* @author   Fabian Siatama
* 
* se definien los metodos para la traduccion de los textos
*/
class Lang
{
	protected static function getPath()
	{
		return PATH_APP.'lang/'.$_SESSION['lang'].'/';
	}

	public static function get($group)
	{
		$path = self::getPath();

		$segments = explode('.', $group);

		$file = array_shift($segments);

		$fileName = $path.$file.'.php';

		$lines = Helpers::getRequire($fileName);

		return Helpers::arrayGet($lines, implode('.', $segments));
		
	}
		
}
