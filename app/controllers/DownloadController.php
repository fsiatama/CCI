<?php

/**
* DownloadController
*
* @category Controller
* @author   Fabian Siatama
* 
* contiene los metodos para descargar archivos
* 
*/
class DownloadController {

	public function excelAction($urlParams, $postParams)
	{
		$fileName = array_shift($urlParams);
		
		return Helpers::download($fileName);
	}

}
