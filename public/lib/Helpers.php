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
		
}
