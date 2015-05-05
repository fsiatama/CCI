<div class="container white">
	<header class="page-header">
		<h3>Posicionamiento y dinamismo de los productos</h3>
	</header>
	<div class="row">

		<div class="col-md-12">
			<p class="lead">
				Análisis dinámico para la diferenciación de productos y mercados según el comportamiento de la demanda (importaciones) y el atractivo comercial que representan los mercados de destino
				<br>
				Para la búsqueda se debe seleccionar :
				<ul>
					<li>
						Primero, un producto de la lista, la cual contiene todos los productos del arancel de aduanas a 6 dígitos
					</li>
					<li>
						Segundo (Opcional), un socio comercial, para esta herramienta los nombres de los países están en ingles. Por ejemplo (USA).
					</li>
				</ul>


				<hr class="half-margins">
			</p>
			<blockquote class="blockquote-reverse">
				<p>
					<cite title="Comtrade">Fuente de información
						<a href="http://comtrade.un.org/data/" target="_blank">Comtrade</a>
					</cite>
				</p>
			</blockquote>
		</div>

		<aside class="col-md-3">

			<form action="#" method="POST" role="form" id="searchQuadrantForm">
				<legend>Filtros</legend>


				<div class="form-group">
					<label for="ms-filter-product" class="margin-bottom10">Productos</label>
					<input type="text" class="form-control" id="ms-filter-product">
				</div>

				<div class="form-group">
					<label for="ms-filter-country" class="margin-bottom10">Socio Comercial</label>
					<input type="text" class="form-control" id="ms-filter-country">
				</div>

				<button type="submit" class="btn btn-primary" id="searchQuadrantSubmit">Buscar</button>
			</form>

		</aside>

		<div class="col-md-9">

			
			<div id="grid-quadrant">
				<div role="tabpanel">
					<ul id="quadrantTabs" class="nav nav-tabs">
						
						<li class="active"><a data-toggle="tab" href="#quadrant_1">Cuadrante 1</a></li>
					
						<li class=""><a data-toggle="tab" href="#quadrant_2" aria-expanded="false">Cuadrante 2</a></li>
					
						<li class=""><a data-toggle="tab" href="#quadrant_3" aria-expanded="true">Cuadrante 3</a></li>
					
						<li class=""><a data-toggle="tab" href="#quadrant_4" aria-expanded="false">Cuadrante 4</a></li>
					
					</ul>
					<div class="tab-content">
						
						<div id="quadrant_1" class="tab-pane active" role="tabpanel">
							<div class="panel panel-primary margin-top10">
								<div class="panel-body text-center">
									<h4>Potenciales</h4>
									<p class="help-block">Mercado pequeño con crecimiento dinámico</p>
									<div id="quadrant_1_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Cuadrante 1</span>
									<!-- <div class="btn-group pull-right">
										<div class="btn-group">
											<a class="btn btn-primary nomargin" id="btn-print-1" href="#" role="button" disabled="disabled"><span class="glyphicon glyphicon-print"></span></a>
										</div>
									</div>
									<div class="clearfix"></div> -->
								</div>
							</div>
						</div>
					
						<div id="quadrant_2" class="tab-pane" role="tabpanel">
							<div class="panel panel-success margin-top10">
								<div class="panel-body text-center">
									<h4>Altamente Atractivos</h4>
									<p class="help-block">Mercado grande con crecimiento dinámico</p>
									<div id="quadrant_2_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Cuadrante 2</span>
									<!-- <div class="btn-group pull-right">
										<div class="btn-group">
											<a class="btn btn-primary nomargin" id="btn-print-2" href="#" role="button" disabled="disabled"><span class="glyphicon glyphicon-print"></span></a>
										</div>
									</div>
									<div class="clearfix"></div> -->
								</div>
							</div>
						</div>
					
						<div id="quadrant_3" class="tab-pane" role="tabpanel">
							<div class="panel panel-danger margin-top10">
								<div class="panel-body text-center">
									<h4>Productos de bajo interés</h4>
									<p class="help-block">Mercado pequeño con bajo crecimiento </p>
									<div id="quadrant_3_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Cuadrante 3</span>
									<!-- <div class="btn-group pull-right">
										<div class="btn-group">
											<a class="btn btn-primary nomargin" id="btn-print-3" href="#" role="button" disabled="disabled"><span class="glyphicon glyphicon-print"></span></a>
										</div>
									</div>
									<div class="clearfix"></div> -->
								</div>
							</div>
						</div>
					
						<div id="quadrant_4" class="tab-pane" role="tabpanel">
							<div class="panel panel-warning margin-top10">
								<div class="panel-body text-center">
									<h4>Promisorios</h4>
									<p class="help-block">Mercado grande con bajo crecimiento</p>
									<div id="quadrant_4_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
								<div class="panel-heading margin-top10">
									<span class="panel-title">Cuadrante 4</span>
									<!-- <div class="btn-group pull-right">
										<div class="btn-group">
											<a class="btn btn-primary nomargin" id="btn-print-4" href="#" role="button" disabled="disabled"><span class="glyphicon glyphicon-print"></span></a>
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
