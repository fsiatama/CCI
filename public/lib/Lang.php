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
		$lang = (empty($_SESSION['lang'])) ? 'es' : $_SESSION['lang'] ;
		return PATH_APP.'lang/'.$lang.'/';
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
