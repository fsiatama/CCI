<div class="container white">
	<header class="page-header">
		<h3>
			Posicionamiento y dinamismo de los productos agropecuarios y agroindustriales en el mercado internacional
		</h3>
	</header>
	<div class="row">

		<div class="col-md-12">
			<p class="lead">
				Esta herramienta permite identificar los productos más atractivos desde el punto de vista del tamaño y del crecimiento promedio anual de las importaciones registrado en los últimos 5 años.
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
			</p>
			<p class="text-right">
				<button type="button" class="btn btn-green btn-lg" data-toggle="modal" data-target="#defaultModal">
				  <i class="fa fa-info-circle"></i> Ayuda metodología cuadrantes
				</button>
			</p>
			<hr class="half-margins">
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

				<button type="submit" class="btn btn-green" id="searchQuadrantSubmit">Buscar</button>
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
							<div class="panel panel-success margin-top10">
								<div class="panel-body text-center">
									<h4>Altamente Atractivos</h4>
									<p class="help-block">Mercado grande con crecimiento dinámico</p>
									<div class="row">
										<div id="quadrant_1_chart_div" class="col-md-12 google-chart"></div>
									</div>
									<blockquote class="blockquote-reverse">
										<p>
											<cite title="Comtrade">Fuente de información
												<a href="http://comtrade.un.org/data/" target="_blank">Comtrade</a>
											</cite>
										</p>
									</blockquote>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Cuadrante 1</span>
									<div class="pull-right">
										<span class="range-year">Periodo (<small></small>)</span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					
						<div id="quadrant_2" class="tab-pane" role="tabpanel">
							<div class="panel panel-success margin-top10">
								<div class="panel-body text-center">
									<h4>Potenciales</h4>
									<p class="help-block">Mercado pequeño con crecimiento dinámico</p>
									<div class="row">
										<div id="quadrant_2_chart_div" class="col-md-12 google-chart"></div>
									</div>
									<blockquote class="blockquote-reverse">
										<p>
											<cite title="Comtrade">Fuente de información
												<a href="http://comtrade.un.org/data/" target="_blank">Comtrade</a>
											</cite>
										</p>
									</blockquote>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Cuadrante 2</span>
									<div class="pull-right">
										<span class="range-year">Periodo (<small></small>)</span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					
						<div id="quadrant_3" class="tab-pane" role="tabpanel">
							<div class="panel panel-success margin-top10">
								<div class="panel-body text-center">
									<h4>Productos de bajo interés</h4>
									<p class="help-block">Mercado pequeño con bajo crecimiento </p>
									<div class="row">
										<div id="quadrant_3_chart_div" class="col-md-12 google-chart"></div>
									</div>
									<blockquote class="blockquote-reverse">
										<p>
											<cite title="Comtrade">Fuente de información
												<a href="http://comtrade.un.org/data/" target="_blank">Comtrade</a>
											</cite>
										</p>
									</blockquote>
								</div>
								<div class="panel-heading">
									<span class="panel-title">Cuadrante 3</span>
									<div class="pull-right">
										<span class="range-year">Periodo (<small></small>)</span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					
						<div id="quadrant_4" class="tab-pane" role="tabpanel">
							<div class="panel panel-success margin-top10">
								<div class="panel-body text-center">
									<h4>Promisorios</h4>
									<p class="help-block">Mercado grande con bajo crecimiento</p>
									<div class="row">
										<div id="quadrant_4_chart_div" class="col-md-12 google-chart"></div>
									</div>
									<blockquote class="blockquote-reverse">
										<p>
											<cite title="Comtrade">Fuente de información
												<a href="http://comtrade.un.org/data/" target="_blank">Comtrade</a>
											</cite>
										</p>
									</blockquote>
								</div>
								<div class="panel-heading margin-top10">
									<span class="panel-title">Cuadrante 4</span>
									<div class="pull-right">
										<span class="range-year">Periodo (<small></small>)</span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="defaultModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Ayuda metodología cuadrantes</h4>
		</div>
		<div class="modal-body">
			<div class="row">
			    <div class="col-xs-offset-1 col-xs-10">
					<p>
						Esta herramienta clasifica los productos en 4 grupos o cuadrantes de acuerdo a su atractivo comercial en función de la demanda internacional, específicamente del tamaño y crecimiento promedio anual de las importaciones de la siguiente manera:
					</p>
					<p class="text-center">
						<img src="<?= URL_RAIZ ?>img/cuadrantes.jpg" class="img-responsive center-block" alt="Responsive image">
					</p>
					<h5><strong>Cuadrante 1. Productos altamente atractivo</strong></h5>
					<p>
						Los productos de este Cuadrante son aquellos en los que el tamaño y el crecimiento promedio anual de las importaciones son mayores a la de los demás productos agropecuarios y agroindustriales. Se trata de un producto con un mercado en expansión y posibilidad para el ingreso de nuevos proveedores.
					</p>
					<h5><strong>Cuadrante 2. Productos potenciales</strong></h5>
					<p>
						Los productos de este Cuadrante son aquellos con un tamaño de mercado inferior al promedio del de los demás productos agropecuarios y agroindustriales, pero con un ritmo de crecimiento mayor. Se trata de productos con un bajo posicionamiento, comparado con el resto de productos del sector, pero con posibilidades para el ingreso de nuevos proveedores.
					</p>
					<h5><strong>Cuadrante 3. Productos de bajo interés</strong></h5>
					<p>
						Los productos de este Cuadrante son aquellos en los que el tamaño y el crecimiento promedio anual de las importaciones son inferiores a la de los demás productos agropecuarios y agroindustriales. Se trata de productos con un mercado poco dinámico o decreciente, comparado con el resto de productos del sector. El ingreso de nuevos proveedores es limitado, y las oportunidades comerciales pueden estar en nichos de mercado.
					</p>
					<h5><strong>Cuadrante 4. Productos promisorios</strong></h5>
					<p>
						Los productos de este Cuadrante son aquellos con un tamaño de mercado mayor al promedio del de los demás productos agropecuarios y agroindustriales, pero con un ritmo de crecimiento menor. Se trata de productos con un mercado maduro o saturado, y en ocasiones en decrecimiento. Es un segmento de difícil acceso que requiere de un alto nivel de competitividad.
					</p>
				</div>
			</div>
		</div>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
