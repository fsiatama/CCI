<?php

/**
* ClassName
*
* @author   Fabian Siatama
* 
* se definien los metodos para la traduccion de los textos
*/
class Download
{
	protected $fileSize;
	protected $fileName;

	private function getPathname()
	{
		return PATH_REPORTS . $this->fileName;
	}

	private function getFileSize()
	{
		$stat = fstat($this->getPathname());
		return $stat['size'];
	}

	private function setExcelHeaders()
	{
		$fileSize = $this->getFileSize();
		header('Content-Length: '.$fileSize);
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 

		@ini_set("zlib.output_compression", "on");
		@set_time_limit(0);
		@ignore_user_abort(true);
	}
	private function sendContent()
	{
		$fileName = array_shift($urlParams);

		$out = fopen('php://output', 'wb');
		$file = fopen($this->getPathname(), 'rb');

		stream_copy_to_stream($file, $out, $this->maxlen, $this->offset);

		fclose($out);
		fclose($file);
	}

	public static function excel($fileName)
	{

		$this->fileName = $fileName;
		$this->setExcelHeaders();
		return $this->sendContent();
	}
		
}
