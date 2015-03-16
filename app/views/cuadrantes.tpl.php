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
						Primero (Opcional), un país, para esta herramienta los nombres de los países están en ingles. Por ejemplo (USA).
					</li>
					<li>
						Segundo, un producto de la lista, la cual contiene todos los productos del arancel de aduanas a 6 dígitos
					</li>
				</ul>


				<hr class="half-margins">
			</p>
		</div>

		<aside class="col-md-3">

			<form action="#" method="POST" role="form" id="searchQuadrantForm">
				<legend>Filtros</legend>

				<div class="form-group">
					<label for="ms-filter-country" class="margin-bottom10">Socio Comercial</label>
					<input type="text" class="form-control" id="ms-filter-country">
				</div>

				<div class="form-group">
					<label for="ms-filter-product" class="margin-bottom10">Productos</label>
					<input type="text" class="form-control" id="ms-filter-product">
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
							<div class="panel panel-default">
								<div class="panel-body text-center">
									<h4>Potenciales</h4>
									<p class="help-block">Mercado pequeño con crecimiento dinámico</p>
									<div id="quadrant_1_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
							</div>
						</div>
					
						<div id="quadrant_2" class="tab-pane" role="tabpanel">
							<div class="panel panel-default">
								<div class="panel-body text-center">
									<h4>Altamente Atractivos</h4>
									<p class="help-block">Mercado grande con crecimiento dinámico</p>
									<div id="quadrant_2_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
							</div>
						</div>
					
						<div id="quadrant_3" class="tab-pane" role="tabpanel">
							<div class="panel panel-default">
								<div class="panel-body text-center">
									<h4>Productos de bajo interés</h4>
									<p class="help-block">Mercado pequeño con bajo crecimiento </p>
									<div id="quadrant_3_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
							</div>
						</div>
					
						<div id="quadrant_4" class="tab-pane" role="tabpanel">
							<div class="panel panel-default">
								<div class="panel-body text-center">
									<h4>Promisorios</h4>
									<p class="help-block">Mercado grande con bajo crecimiento</p>
									<div id="quadrant_4_chart_div" style="width: 700px; height: 400px;"></div>
								</div>
							</div>
						</div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
