<?php

require_once(PATH_APP.'lib/PHPExcel/Classes/PHPExcel.php');

/**
* Excel
*
* @author   Fabian Siatama
*
* Esta clase permite exportar cualquier grilla en formato EXCEL o PDF
*/
class Excel
{
	private $data;
	private $format;
	private $head;
	private $total;
	private $fileName;
	private $columnFormat;
	private $rendererName;
	private $rendererLibraryPath;
	private $objPHPExcel;
	private $rowNumber = 1;
	private $worksheet;
	private $numberColumns = 0;
	
	public function __construct($data, $format, $head, $total, $fileName, $columnFormat = "")
	{
		$this->data         = $data;
		$this->format       = $format;
		$this->head         = $head;
		$this->total        = $total;
		$this->fileName     = $fileName;
		$this->columnFormat = $columnFormat;

		$this->rendererName        = PHPExcel_Settings::PDF_RENDERER_MPDF;
		$rendererLibrary           = 'MPDF54';
		$this->rendererLibraryPath = PATH_APP.'lib/' . $rendererLibrary;
		$this->objPHPExcel         = new PHPExcel();
		$this->setProperties();
		$this->objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$this->objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
	}

	private function setRowNumber($rowNumber)
	{
		$this->rowNumber = $rowNumber;
	}

	private function setProperties()
	{
		// Set properties
		$this->objPHPExcel->getProperties()->setCreator("Fabian Siatama")
									 ->setTitle("Quintero Hermanos Ltda Document www.sicex.com")
									 ->setSubject("Quintero Hermanos Ltda. Document www.sicex.com")
									 ->setDescription("Reporte generado desde www.sicex.com")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("www.sicex.com");
	}

	private function letterColumn($number)
	{
		$residue = $number % 26;
		$integer = floor(($number / 26));
		
		if ($number > 26) {
			$secondLetter = ($residue == 0) ? $residue + 1 : $residue;
			$letter = chr($integer + 64).$this->letterColumn($secondLetter);
		} else {

			if ($number == 26) {
				$letter = chr(90);
			} else {
				$letter = chr($residue + 64);
			}
		}
		return $letter;
	}

	private function writeHeader()
	{
		$cell = 'A';
		
		foreach($this->head as $fieldName => $fieldTitle) {
			$this->numberColumns += 1;

			$cell = $this->letterColumn($this->numberColumns);

			$this->worksheet->getColumnDimension($cell)->setAutoSize(true);
			$this->worksheet->setCellValueByColumnAndRow( ($this->numberColumns-1), $this->rowNumber, utf8_encode($fieldTitle) );
			
			$arrHead[$cell] = $fieldTitle;
		}

		$this->objPHPExcel->getActiveSheet()->getStyle('A'.$this->rowNumber.':'.$cell.$this->rowNumber)->applyFromArray(
			[
				'font' => [
					'bold'  => true,
					'color' => [ 'argb' => PHPExcel_Style_Color::COLOR_WHITE ]
				],
				'alignment' => [
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				],
				'borders' => [
					'top'     => [ 'style' => PHPExcel_Style_Border::BORDER_THIN ]
				],
				'fill' => [
					'type'       => PHPExcel_Style_Fill::FILL_SOLID,
					'startcolor' => [ 'argb' => 'FF1F497D' ]
				]
			]
		);
	}

	private function writeBody()
	{
		$arrData = [];

		foreach ($this->data as $key => $dataRow) {

			foreach ($dataRow as $subKey => $dataCell) {
				
				if (is_array($this->head)) {
					
					if (array_key_exists($subKey, $this->head)) {
						
						$index = array_search($subKey,array_keys($this->$head));

						$arrData[$key][$index] = $dataCell;

					}

				}

			}

		}
		$rowNumber = $this->rowNumber;

		foreach ($arrData as $key => $data) {
			$rowNumber += 1;
			$this->setRowNumber($rowNumber);

			ksort($data);
			$this->worksheet->fromArray( $data, NULL, 'A'.$this->rowNumber);
		}

	}

	private function save()
	{
		switch ($this->format){
	    	case '1':
				$fileName = $this->fileName.'.xlsx';
				$objWriter = new PHPExcel_Writer_Excel2007($this->objPHPExcel);
			break;
			case '2':
				$fileName = $this->fileName.'.xls';
				$this->objPHPExcel->getActiveSheet()->setShowGridLines(false);
				$objWriter = new PHPExcel_Writer_Excel5($this->objPHPExcel);
			break;
			case '3':
				$fileName = $this->fileName.'.pdf';
				$archivo = $nombre.'.pdf';
				$this->objPHPExcel->getActiveSheet()->setShowGridLines(false);
				
				if (!PHPExcel_Settings::setPdfRenderer(
						$this->rendererName,
						$this->rendererLibraryPath
					)) {
					die(
						'NOTICE: Please set the '.$rendererName .' and .'.$rendererLibraryPath .'values' .
						'<br />' .
						'at the top of this script as appropriate for your directory structure'
					);
				}
				$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'PDF');
				$objWriter->setSheetIndex(0);
			break;
			case '4':
				$fileName = $this->fileName.'.txt';
				$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'CSV')->setDelimiter(',')
	                                                                  ->setEnclosure('"')
	                                                                  ->setLineEnding("\r\n")
	                                                                  ->setSheetIndex(0);
			break;
		}
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save(PATH_REPORTS.$fileName);
		return $fileName;
	}

	public function write()
	{
		$rowNumber = 4;
		$this->setRowNumber($rowNumber);
		$this->worksheet = $this->objPHPExcel->getActiveSheet();

		$this->writeHeader();
		
		$this->writeBody();

		return $this->save();
	}
}