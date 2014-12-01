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
		return date('Y-m-d H:i:s');
	}
	
	public static function getDateNow()
	{
		return date('Y-m-d');
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
	public static function jsonChart($arr_data, $eje_x, $series, $typeChart, $serie_adicional = false)
	{

		$arrCategories = array();
		$rowData       = array();
		
		$arr_cols      = array();
		
		$arr_chart          = [];

		$arr_chart['chart'] = [
			'decimalprecision'   =>'2'
			,'palette'           =>'4'
			,'formatnumberscale' =>'1'
			,'numberscalevalue'  =>'1000000'
			,'numberscaleunit'   =>'M'
			,'rotatevalues'      =>'1'
			,'divlineisdashed'   =>'1'
			,'placevaluesinside' =>'1'
			,'exportenabled'     =>'0'
			,'areaovercolumns'   =>'0'
			,'showaboutmenuitem' =>'0'
			,'showlabels'        =>'1'
			,'showBorder'        =>'0'
			,'palettecolors'     => '#008ee4,#6baa01,#f8bd19,#e44a00,#33bdda,#d35400,#bdc3c7,#95a5a6,#34495e,#1abc9c'
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
				
				$tooltext = $row[$eje_x];

				if ($key == $eje_x) {
					
					$arrCategories['category'][] = ['label' => $value];

				} elseif (array_key_exists($key, $series)) {

					$seriesname = $series[$key];

					$rowData[$seriesname][] = [
						'value'    => $value,
						'tooltext' => number_format($value,2)
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

		$arr_chart['styles']['definition'] = [
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
		];


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

	public static function getPeriodColumnSql($period)
	{
		$column = 'anio AS periodo';
		switch ($period) {
			case 6:
				$column = '
					(CASE 
					   WHEN 0 < periodo AND periodo <= 6 THEN "1"
					   WHEN 6 < periodo THEN "2"
					 END
					) AS periodo
				';
			break;
			case 3:
				$column = '
					(CASE 
					   WHEN 0 < periodo AND periodo <= 3 THEN "1"
					   WHEN 3 < periodo AND periodo <= 6 THEN "2"
					   WHEN 6 < periodo AND periodo <= 9 THEN "3"
					   WHEN 9 < periodo THEN "4"
					 END
					) AS periodo
				';
			break;
			case 1:
				$column = 'periodo';
			break;
		}
		return $column;
	}
		
}
