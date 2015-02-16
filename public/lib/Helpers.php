<?php

use Apfelbox\FileDownload\FileDownload;
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
		return date('Y-m-d H:i:s');
	}

	public static function getDateNow()
	{
		return date('Y-m-d');
	}

	public static function getDate($string)
	{
		return DateTime::createFromFormat('Y-m-d', $string);
	}

	public static function arraySortByValue($key, $desc = false)
	{
		return function ($a, $b) use ($key, $desc) {
			$operator = ($desc) ? -1 : 1 ;
			return $operator * strnatcmp($a[$key], $b[$key]);
		};
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
		exit('File does not exist at path {$path}');
	}

	public static function filterValuesToArray($value)
	{
		$arrFilters = explode('||', $value);
		$arrFields  = [];

		foreach($arrFilters as $key => $filter) {
			$field = explode(':', $filter);

			$arrFields[$field[0]] = $field[1];
		}

		return $arrFields;
	}

	/**
	 * jsonChart
	 *
	 * @param mixed $arr_data        array con los datos.
	 * @param mixed $eje_x           Description.
	 * @param mixed $filas           Description.
	 * @param mixed $columnas        Description.
	 * @param mixed $typeChart		 Description.
	 * @param mixed $serie_adicional Description.
	 *
	 * @access public
	 * @static
	 *
	 * @return mixed Value.
	 */
	public static function jsonChart($arr_data, $eje_x, $series, $typeChart, $xAxisname = '', $pYAxisName = '')
	{
		$arrCategories = [];
		$rowData       = [];
		$arr_cols      = [];
		$arr_chart     = [];

		$arr_chart['xAxis'] = $eje_x;
		$arr_chart['series'] = $series;

		$arr_chart['chart'] = [
			'decimalprecision'     => '4'
			,'palette'             => '4'
			,'formatnumberscale'   => '0'
			/*,'numberscalevalue'    => '1000000'
			,'numberscaleunit'     => 'M'*/
			,'theme'               => 'fint'
			,'xAxisname'           => $xAxisname
			,'yaxisname'           => $pYAxisName
			,'theme'               => 'fint'
			,'rotatevalues'        => '1'
			,'divlineisdashed'     => '1'
			,'placevaluesinside'   => '1'
			,'exportenabled'       => '1'
			,'showPercentValues'   => '1'
			,'areaovercolumns'     => '0'
			,'showaboutmenuitem'   => '0'
			,'showlabels'          => '1'
			,'showBorder'          => '0'
			,'palettecolors'       => '#008ee4,#6baa01,#f8bd19,#e44a00,#33bdda,#d35400,#bdc3c7,#95a5a6,#34495e,#1abc9c'
		];

		if ($typeChart == LINEAL || $typeChart == AREA) {
			$arr_chart['chart']['showvalues'] = '0';
			$arr_chart['chart']['palette']    = '1';
		} elseif ($typeChart == PIE) {
			$arr_chart['chart']['forcedecimals'] = '1';
			$arr_chart['chart']['showlabels']    = '0';
			$arr_chart['chart']['showlegend']    = '1';
		}

		foreach ($arr_data as $row) {

			$seriesname = '';

			foreach ($row as $key => $value) {

				$label = $row[$eje_x];

				if ($key == $eje_x) {

					$arrCategories['category'][] = ['label' => $value];

				} elseif (array_key_exists($key, $series)) {

					$seriesname = $series[$key];

					$tooltext = ($typeChart == PIE) ? $label : number_format($value,2) ;

					$rowData[$seriesname][] = [
						'value'    => $value,
						'tooltext' => $tooltext,
						'label'    => $label
					];

				}
			}

		}

		$arr_chart['categories'] = $arrCategories;

		foreach ($rowData as $key => $value) {
			$arr_chart['dataset'][] = [
				'seriesname' => $key,
				'data' => $value
			];
		}

		/*$arr_chart['styles']['definition'] = [
			[
				'name'     => 'Anim1',
				'type'     => 'animation',
				'param'    => '_xscale',
				'start'    => '0',
				'duration' => '1'
			],[
				'name'     => 'Anim2',
				'type'     => 'animation',
				'param'    => '_alpha',
				'start'    => '0',
				'duration' => '1'
			],[
				'name'  => 'DataShadow',
				'type'  => 'Shadow',
				'alpha' => '20'
			]
		];
		$arr_chart['application'] = [
			[
				'toobject' => 'DIVLINES',
				'styles'   => 'Anim1'
			],[
				'toobject' => 'HGRID',
				'styles'   => 'Anim2'
			],[
				'toobject' => 'DATALABELS',
				'styles'   => 'DataShadow'
			],[
				'toobject' => 'DATALABELS',
				'styles'   => 'Anim2'
			]
		];*/


		return $arr_chart;









		$origCampo    = false;
		$arr_tmp2     = array();


		foreach ($arr_data as $data) {
			$arr_tmp['data'] = [];

			$seriesname = '';

			foreach ($data as $key => $valor) {

				$nombre_columna = $key;

				if ($origCampo) {
					$nombre_columna = $origCampo['nombre'];
				}

				$tooltext = $data[$eje_x];
				if ($key == $eje_x) {
					$seriesname = $valor;
					$arr_tmp2['categorias']['category'][] = ['label' => $valor];
				} else {
					//var_dump($key, $filas);
					if (!in_array($key, $filas)) {

						$arr_cols[$key]                      = $key;
						$arr_tmp['data'][]                   = ['value' => $valor, 'tooltext' => $tooltext, 'label' => $nombre_columna];
						$arr_tmp2['data'][$nombre_columna][] = ['value' => $valor, 'tooltext' => $valor, 'label' => $tooltext];
					}
				}
			}
			$arr_chart['dataset'][] = ['seriesname' => $seriesname, 'data' => $arr_tmp['data'] ];
		}
		//print_r($arr_tmp2['categorias']);
		//var_dump($arr_cols);

		foreach ($arr_cols as $key => $data) {

			$arr_tmp['categorias']['category'][] = [ 'label' => $data ];

		}

		if((($typeChart == LINEAL || $typeChart == AREA) && count($arr_cols) <= 2) || $typeChart == PIE ){

			$arr_chart['categories'] = array_merge( $arr_tmp2['categorias'], ['font' => 'Arial','fontsize' => '8', 'fontcolor' => '000000'] );

			$arr_chart['dataset'] = [];

			foreach ($arr_tmp2['data'] as $key => $data) {
				$arr_chart['dataset'][] = ['seriesname'=>$key, 'data'=>$data];
			}
		} else{
			$arr_chart['categories'] = array_merge( $arr_tmp['categorias'], ['font' => 'Arial','fontsize' => '8', 'fontcolor' => '000000'] );
		}
		if ($serie_adicional && is_array($serie_adicional)) {
			$arr_chart['dataset'][] = $serie_adicional;
		}



		//print_r($arr_chart);
		return $arr_chart;
	}

	public static function getPeriodColumnSql($period, $withPeriodName = true, $fieldPeriodName = '')
	{
		$fieldPeriodName = (empty($fieldPeriodName)) ? 'periodo' : $fieldPeriodName ;
		$column          = 'anio AS ' . $fieldPeriodName;
		$periodName      = '""';
		switch ($period) {
			case 6:
				if ($withPeriodName) {
					$periodName = 'anio, " '.Lang::get('indicador.reports.semester').' "';
				}
				$column = '
					(CASE
					   WHEN 0 < ' . $fieldPeriodName . ' AND ' . $fieldPeriodName . ' <= 6 THEN CONCAT('.$periodName.', "1")
					   WHEN 6 < ' . $fieldPeriodName . ' THEN CONCAT('.$periodName.', "2")
					 END
					) AS ' . $fieldPeriodName . '
				';
			break;
			case 3:
				if ($withPeriodName) {
					$periodName = 'anio, " '.Lang::get('indicador.reports.quarter').' "';
				}
				$column = '
					(CASE
					   WHEN 0 < ' . $fieldPeriodName . ' AND ' . $fieldPeriodName . ' <= 3 THEN CONCAT('.$periodName.', "1")
					   WHEN 3 < ' . $fieldPeriodName . ' AND ' . $fieldPeriodName . ' <= 6 THEN CONCAT('.$periodName.', "2")
					   WHEN 6 < ' . $fieldPeriodName . ' AND ' . $fieldPeriodName . ' <= 9 THEN CONCAT('.$periodName.', "3")
					   WHEN 9 < ' . $fieldPeriodName . ' THEN CONCAT('.$periodName.', "4")
					 END
					) AS ' . $fieldPeriodName . '
				';
			break;
			case 1:
				$periodName1  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.1')  . '"' : '1' ;
				$periodName2  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.2')  . '"' : '2' ;
				$periodName3  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.3')  . '"' : '3' ;
				$periodName4  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.4')  . '"' : '4' ;
				$periodName5  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.5')  . '"' : '5' ;
				$periodName6  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.6')  . '"' : '6' ;
				$periodName7  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.7')  . '"' : '7' ;
				$periodName8  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.8')  . '"' : '8' ;
				$periodName9  = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.9')  . '"' : '9' ;
				$periodName10 = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.10') . '"' : '10' ;
				$periodName11 = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.11') . '"' : '11' ;
				$periodName12 = ($withPeriodName) ? 'anio, " ' . Lang::get('indicador.months.12') . '"' : '12' ;
				$column = '
					(CASE
					   WHEN 1  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName1 . ')
					   WHEN 2  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName2 . ')
					   WHEN 3  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName3 . ')
					   WHEN 4  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName4 . ')
					   WHEN 5  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName5 . ')
					   WHEN 6  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName6 . ')
					   WHEN 7  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName7 . ')
					   WHEN 8  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName8 . ')
					   WHEN 9  = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName9 . ')
					   WHEN 10 = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName10 . ')
					   WHEN 11 = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName11 . ')
					   WHEN 12 = ' . $fieldPeriodName . ' THEN CONCAT(' . $periodName12 . ')
					 END
					) AS ' . $fieldPeriodName . '
				';
			break;
		}
		return $column;
	}

	public static function getPeriodName($period, $number)
	{
		$periodName = Lang::get('indicador.reports.annual');
		switch ($period) {
			case 6:
				$periodName = Lang::get('indicador.reports.semester').' '.$number;
			break;
			case 3:
				$periodName = Lang::get('indicador.reports.quarter').' '.$number;
			break;
			case 1:
				$periodName = Lang::get('indicador.months.'.$number);
			break;
		}
		return $periodName;
	}

	public static function getPeriodRange($period)
	{
		$periodRange = [];
		switch ($period) {
			case 12:
				$periodRange = [
					1 => ['1']
				];
			break;
			case 6:
				$periodRange = [
					1 => ['1','2','3','4','5','6'],
					2 => ['7','8','9','10','11','12']
				];
			break;
			case 3:
				$periodRange = [
					1 => ['1','2','3'],
					2 => ['4','5','6'],
					3 => ['7','8','9'],
					4 => ['10','11','12']
				];
			break;
			case 1:
				$periodRange = [
					1  => ['1'],
					2  => ['2'],
					3  => ['3'],
					4  => ['4'],
					5  => ['5'],
					6  => ['6'],
					7  => ['7'],
					8  => ['8'],
					9  => ['9'],
					10 => ['10'],
					11 => ['11'],
					12 => ['12']
				];
			break;
		}
		return $periodRange;
	}

	public static function findKeyInArrayMulti($array, $key, $value)
	{
		foreach ($array as $subarray){

			if (isset($subarray[$key]) && $subarray[$key] == $value) {
				return $subarray;
			}
		}

		return false;
	}

	public static function download($fileName, $deleteFile = true)
	{
		$filePath = PATH_REPORTS . $fileName;

		$fileDownload = FileDownload::createFromFilePath($filePath);
		
		$result = $fileDownload->sendDownload($fileName);

		if ($deleteFile) {
			unlink($filePath);
		}
		return $result;
	}

	public static function getUpdateInfo($product, $trade)
	{
		//require_once PATH_MODELS.'Repositories/Update_infoRepo.php';

		self::getRequire(PATH_MODELS.'Repositories/Update_infoRepo.php');
		$update_infoRepo = new Update_infoRepo;

		$result = $update_infoRepo->updateInfo(compact('product', 'trade'));

		if (!$result['success'] || $result['total'] == 0) {
			return false;
		}

		$row = array_shift($result['data']);

		$dateFrom = self::getDate($row['update_info_from']);
		$dateTo   = self::getDate($row['update_info_to']);

		$yearsAvailable = range($dateFrom->format('Y'), $dateTo->format('Y'));

		return compact('dateFrom', 'dateTo', 'yearsAvailable');

	}

}
