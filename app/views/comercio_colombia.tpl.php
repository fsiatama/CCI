<?php 

$updateInfoExpo = ( $updateInfoExpo !== false ) ? Lang::get('shared.months.'.$updateInfoExpo['dateTo']->format('m')).' - '.$updateInfoExpo['dateTo']->format('Y') : '' ;
$updateInfoImpo = ( $updateInfoImpo !== false ) ? Lang::get('shared.months.'.$updateInfoImpo['dateTo']->format('m')).' - '.$updateInfoImpo['dateTo']->format('Y') : '' ;

$updateInfo = '
	' . Lang::get('indicador.reports.impo') . ' ' . Lang::get('update_info.columns_title.update_info_to') . ': ' . $updateInfoImpo . '
	<br>
	' . Lang::get('indicador.reports.expo') . ' ' . Lang::get('update_info.columns_title.update_info_to') . ': ' . $updateInfoExpo . '
';

$updateInfo = Inflector::compress($updateInfo);

?>

<div class="container white">
	<header class="page-header">
		<h3>Comercio agropecuario de <strong>Colombia</strong></h3>
	</header>
	<div class="row">

		<aside class="col-md-3">

			<h4>REPORTES</h4>
			<ul class="nav nav-list" id="trade-reports">
				<li><a href="#" data-report="colombia-al-mundo"><i class="fa fa-angle-right"></i> Colombia al Mundo</a></li>
				<li><a href="#" data-report="principales-destinos"><i class="fa fa-angle-right"></i> Principales destinos</a></li>
				<li><a href="#" data-report="principales-origenes"><i class="fa fa-angle-right"></i> Principales orígenes</a></li>
			</ul>

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


