<?php 

$updateInfoExpo = ( $updateInfoExpo !== false ) ? Lang::get('shared.months.'.$updateInfoExpo['dateTo']->format('m')).' - '.$updateInfoExpo['dateTo']->format('Y') : '' ;
$updateInfoImpo = ( $updateInfoImpo !== false ) ? Lang::get('shared.months.'.$updateInfoImpo['dateTo']->format('m')).' - '.$updateInfoImpo['dateTo']->format('Y') : '' ;

$updateInfo = '
	' . Lang::get('indicador.reports.impo') . ' ' . Lang::get('update_info.columns_title.update_info_to') . ': ' . $updateInfoImpo . '
	<br>
	' . Lang::get('indicador.reports.expo') . ' ' . Lang::get('update_info.columns_title.update_info_to') . ': ' . $updateInfoExpo . '
';

$updateInfo = Inflector::compress($updateInfo);

$htmlActivator = '';
//var_dump($activator);
foreach ($activator as $key => $row) {

	$checked = ($key == 0) ?  'checked="checked"' : '';
	$htmlActivator .= '
		<div class="radio">
		  <label>
		    <input type="radio" name="typeIndicator" value="' . $row[0] . '" '.$checked.'>
		    ' . $row[1] . '
		  </label>
		</div>
	';
}

?>

<div class="container white">
	<header class="page-header">
		<h3>Comercio agropecuario de <strong>Colombia</strong></h3>
	</header>
	<div class="row">

		<aside class="col-md-3">

			<form action="#" method="POST" role="form" id="publicReportsForm">

				<div class="form-group">
					<legend>REPORTES</legend>

					<div class="radio">
					  <label>
					    <input type="radio" name="report" value="colombia-al-mundo" checked="checked">
					    Colombia al Mundo
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="report" value="principales-destinos">
					    Principales destinos
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="report" value="principales-origenes">
					    Principales orígenes
					  </label>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="radios"><?= Lang::get('tipo_indicador.columns_title.tipo_indicador_activador'); ?></label>
					<?= $htmlActivator; ?>
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-primary" id="publicReportsSubmit">Consultar</button>
				</div>
			</form>
		</aside>

		<div class="col-md-9">

			<div class="">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?= Lang::get('update_info.table_name'); ?>
					</div>
					<div class="panel-body">
						<?= $updateInfo; ?>
					</div>
				</div>
			</div>

			
			<div id="grid-trade-info">

				<p class="lead"><i class="fa fa-arrow-left"></i> Por favor seleccione un reporte.</p>
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>


