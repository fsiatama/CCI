
<div class="container white">
	<header class="page-header">
		<h3>Información general de los tratados de libre comercio (TLC)</h3>
	</header>
	<div class="row">

		<div class="col-md-12">
			<p class="lead">
				Aquí podrá encontrar fácilmente información general de los acuerdos comerciales entre Colombia y otros países, 
				para los productos agropecuarios y agroindustriales.
				<ul>
					<li>
						Seleccione un país en la parte inferior del listado desplegable, sección "Socio Comercial".
					</li>
					<li>
						Una vez lo haga, oprima el botón buscar.
					</li>
					<li>
						A continuación, se resaltará en el mapa los países incluidos en un acuerdo comercial.
					</li>
				</ul>
				<hr class="half-margins">
			</p>
		</div>



		<aside class="col-md-3">

			<form action="#" method="POST" role="form" id="searchAgreementForm">
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

				<!-- <div class="form-group">
					<label for="ms-filter-product" class="margin-bottom10">Productos</label>
					<input type="text" class="form-control" id="ms-filter-product">
				</div> -->

				<div class="form-group">
					<label for="ms-filter-country" class="margin-bottom10">Socio Comercial</label>
					<input type="text" class="form-control" id="ms-filter-country">
				</div>

				<button type="submit" class="btn btn-primary" id="searchAgreementSubmit">Buscar</button>
			</form>

		</aside>

		<div class="col-md-9">

			<div class="panel panel-default">
				<div class="panel-heading">

				</div>
				<div class="panel-body">
					<div id="map-canvas" style="height: 600px;"></div>
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


<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>