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
				<div role="tabpanel">
					<ul id="quadrantTabs" class="nav nav-tabs">
						
						<li class="active"><a data-toggle="tab" href="#chart_1">Participación</a></li>
					
						<li class=""><a data-toggle="tab" href="#chart_2" aria-expanded="false">Tasa de crecimiento</a></li>
						<li class=""><a data-toggle="tab" href="#data" aria-expanded="false">Datos</a></li>
					
					</ul>
					<div class="tab-content">
						
						<div id="chart_1" class="tab-pane active" role="tabpanel">
							<div class="panel panel-primary margin-top10">
								<div class="panel-body text-center">
									<h4>Principales Productos</h4>
									<p class="help-block">Valor acumulado últimos 5 años</p>
									<div id="chart_1_div"></div>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Participación Por Producto</span>
									<!-- <div class="btn-group pull-right">
										<div class="btn-group">
											<a class="btn btn-primary nomargin" id="btn-print-1" href="#" role="button" disabled="disabled"><span class="glyphicon glyphicon-print"></span></a>
										</div>
									</div>
									<div class="clearfix"></div> -->
								</div>
							</div>
						</div>
					
						<div id="chart_2" class="tab-pane" role="tabpanel">
							<div class="panel panel-success margin-top10">
								<div class="panel-body text-center">
									<h4>Altamente Atractivos</h4>
									<p class="help-block">Mercado grande con crecimiento dinámico</p>
									<div id="quadrant_2_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Tasa de crecimiento</span>
									<!-- <div class="btn-group pull-right">
										<div class="btn-group">
											<a class="btn btn-primary nomargin" id="btn-print-2" href="#" role="button" disabled="disabled"><span class="glyphicon glyphicon-print"></span></a>
										</div>
									</div>
									<div class="clearfix"></div> -->
								</div>
							</div>
						</div>
						<div id="data" class="tab-pane" role="tabpanel">
							<div class="panel panel-success margin-top10">
								<div class="panel-body text-center">
									<h4>Altamente Atractivos</h4>
									<p class="help-block">Mercado grande con crecimiento dinámico</p>
									<div id="quadrant_2_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Datos acumulados</span>
									<!-- <div class="btn-group pull-right">
										<div class="btn-group">
											<a class="btn btn-primary nomargin" id="btn-print-2" href="#" role="button" disabled="disabled"><span class="glyphicon glyphicon-print"></span></a>
										</div>
									</div>
									<div class="clearfix"></div> -->
								</div>
							</div>
						</div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>


