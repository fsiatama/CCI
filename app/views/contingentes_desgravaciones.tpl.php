
<div class="container white">
	<header class="page-header">
		<h3>Cronograma de desgravación y contingentes otorgados</h3>
	</header>
	<div class="row">

		<div class="col-md-12">
			<p class="lead">
				Aquí podrá consultar Las desgravaciones otorgadas a Colombia por producto, por País y con los años proyectados, así como los tamaños de los contingentes y la tasa extra contingente.
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
						Tercero, un producto de la lista, la cual contiene todos los productos del acuerdo comercial con el país seleccionado anteriormente
					</li>
				</ul>
				<hr class="half-margins">
			</p>
		</div>



		<aside class="col-md-3">

			<form action="#" method="POST" role="form" id="searchQuotaForm">
				<legend>Filtros</legend>

				<div class="radio">
				  <label>
				    <input type="radio" name="agreementTrade" id="optionsRadios1" value="impo" checked="checked">
				    Soy un importador
				  </label>
				</div>
				<div class="radio">
				  <label>
				    <input type="radio" name="agreementTrade" id="optionsRadios2" value="expo">
				    Soy un exportador
				  </label>
				</div>
				
				<div class="form-group">
					<label for="ms-filter-country">Socio Comercial</label>
					<input type="text" class="form-control" id="ms-filter-country">
				</div>

				<div class="form-group">
					<label for="ms-filter-product">Productos</label>
					<input type="text" class="form-control" id="ms-filter-product">
				</div>


				<button type="submit" class="btn btn-primary" id="searchQuotaSubmit">Buscar</button>
			</form>

		</aside>

		<div class="col-md-9">

			<div class="panel panel-default">
				<div class="panel-heading">

				</div>
				<div class="panel-body">
					<div id="grid-quota"></div>
				</div>
			</div>

		</div>
	</div>
</div>



<div class="modal" id="agreementModal" tabindex="-1" role="dialog" aria-labelledby="agreementModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			

		</div>
	</div>
</div>