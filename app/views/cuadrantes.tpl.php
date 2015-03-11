
<div class="container white">
	<header class="page-header">
		<h3>Posicionamiento y dinamismo de los productos</h3>
	</header>
	<div class="row">

		<div class="col-md-12">
			<p class="lead">
				Análisis dinámico para la diferenciación de productos y mercados según el comportamiento de la demanda (importaciones) y el atractivo comercial que representan los mercados de destino
				<br>
				Para la búsqueda se debe seleccionar:
				<ul>
					<li>
						Primero si desea importar o exportar productos.
					</li>
					<li>
						Segundo, un país con los que existen acuerdos comerciales.
					</li>
					<li>
						Tercero, un producto de la lista, la cual contiene todos los productos del arancel de aduanas a 6 dígitos
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

			<div class="panel panel-default">
				<div class="panel-heading">

				</div>
				<div class="panel-body">
					<div id="grid-quadrant"></div>
				</div>
			</div>

		</div>
	</div>
</div>
