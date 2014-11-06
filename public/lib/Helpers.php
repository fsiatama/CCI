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
	
	public static function arrayToVar($array)
	{
		if (is_array($array)) {
			$arr_vars = array();
			foreach ($array as $key => $value) {
				$$key = $value;
				$arr_vars[] = $key;
			}
			return compact( $arr_vars );
		}
	}
}
