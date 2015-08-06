<?php

$tableHtml = '
<table id="data-table" cellpadding="0" cellspacing="0" border="0" class="table table-condensed table-striped table-bordered" width="100%">
  <thead>
  	<tr>
';

foreach ($htmlColumns as $key => $value) {
	if ($key != 'subpartida' && $key != 'id_subpartida') {
		$tableHtml .= '<th class="text-right" data-dynatable-column="'.$key.'">' . $value . '</th>';
	} else {
		$tableHtml .= '<th data-dynatable-column="'.$key.'">' . $value . '</th>';
	}
}

$tableHtml .= '
  		</tr>
  	</thead>
  <tbody>
';

foreach ($data as $row) {
	$tableHtml .= '<tr>';
	foreach ($row as $key => $value) {
		
		if ( ! empty($htmlColumns[$key]) ) {
			if (is_numeric($value) && $key != 'id_subpartida') {
				$tableHtml .= '<td>' . number_format($value, 2) . '</td>';
			} else {
				$tableHtml .= '<td>' . substr($value, 0, 50) . '</td>';
			}
		}
	}
	$tableHtml .= '</tr>';
}

$tableHtml .= '
  </tbody>
</table>
';

$yearFirst = array_shift($arrYear);
$yearLast  = array_pop($arrYear);

?>

<div role="tabpanel">
	<ul id="quadrantTabs" class="nav nav-tabs">
		
		<li class="active"><a data-toggle="tab" href="#chart_1">Participación</a></li>
		<li class=""><a data-toggle="tab" href="#chart_2" aria-expanded="false">Tasa de crecimiento</a></li>
		<li class=""><a data-toggle="tab" href="#data_3" aria-expanded="false">Datos</a></li>
	
	</ul>
	<div class="tab-content">
		
		<div id="chart_1" class="tab-pane active" role="tabpanel">
			<div class="panel panel-primary margin-top10">
				<div class="panel-body text-center">
					<h4>Principales Productos</h4>
					<p class="help-block">Valor promedio de los últimos 5 años <small>(<?= $yearFirst . ' - ' . $yearLast; ?>)</small></p>
					<div id="chart_1_div" style="width:100%; height:500px"></div>
					<blockquote class="blockquote-reverse">
						<p>
							<cite title="DIAN">Fuente de información
								<a href="http://www.dian.gov.co/" target="_blank"> DIAN</a>
							</cite>
						</p>
					</blockquote>
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
					<h4>Principales Productos</h4>
					<p class="help-block">Valor promedio de los últimos 5 años <small>(<?= $yearFirst . ' - ' . $yearLast; ?>)</small></p>
					<div id="chart_2_div" style="width:700px; height:500px"></div>
					<blockquote class="blockquote-reverse">
						<p>
							<cite title="DIAN">Fuente de información
								<a href="http://www.dian.gov.co/" target="_blank"> DIAN</a>
							</cite>
						</p>
					</blockquote>
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
		<div id="data_3" class="tab-pane" role="tabpanel">
			<div class="panel panel-success margin-top10">
				<div class="panel-body text-center">
					<h4>Principales Productos</h4>
					<p class="help-block">Valor promedio de los últimos 5 años <small>(<?= $yearFirst . ' - ' . $yearLast; ?>)</small></p>
					<div class="table-responsive fsize11">
							<?= $tableHtml; ?>
					</div>
					<blockquote class="blockquote-reverse">
						<p>
							<cite title="DIAN">Fuente de información
								<a href="http://www.dian.gov.co/" target="_blank"> DIAN</a>
							</cite>
						</p>
					</blockquote>
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