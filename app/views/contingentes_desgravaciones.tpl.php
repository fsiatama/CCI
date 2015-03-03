
<div class="container white">
	<header class="page-header">
		<h3>Información general de los Tratados de Libre Comercio (TLC)</h3>
	</header>
	<div class="row">

		<div class="col-md-12">
			<p class="lead">
				Aquí podrá encontrar fácilmente información de los acuerdos comerciales entre Colombia y otros países, para los productos agropecuarios y agroindustriales.
				<ul>
					<li>
						Puede buscar por productos, digitando la descripción o el capítulo, partida, subpartida o posición arancelaria (Es decir según el arancel de aduanas).
					</li>
					<li>
						También puede buscar directamente un socio comercial (país), para ver la información del acuerdo comercial.
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

				<div class="form-group">
					<label for="ms-filter-product">Productos</label>
					<input type="text" class="form-control" id="ms-filter-product">
				</div>

				<div class="form-group">
					<label for="ms-filter-country">Socio Comercial</label>
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