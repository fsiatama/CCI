<?php 
$flag = '';

if (empty($row['acuerdo_mercado_id'])) {
	$flag = 'img/flags/64/'.$row['pais_iata'].'.png';
} else {
	$flag = 'img/flags/markets/'.$row['mercado_bandera'].'.png';
}

$flag = ( file_exists(PATH_RAIZ.$flag) ) ? URL_RAIZ.$flag : '' ;

$acuerdo_ffirma_title = ( empty($row['acuerdo_ffirma_title']) ) ? '' : $row['acuerdo_ffirma_title'] ;

$partner = ( empty($row['mercado_nombre']) ) ? $row['pais'] : $row['mercado_nombre'] ;

?>


<div class="modal-header"><!-- modal header -->
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h5 class="modal-title" id="agreementModalLabel"><i class="fa fa-globe"></i>  <?= $partner; ?></h5>
</div><!-- /modal header -->

<div class="modal-body"><!-- modal body -->

	<div class="media">
		<div class="media-left" id="partnerFlag">
			<!-- <img src="holder.js/300x200"> -->
			<img src="<?= $flag; ?>">
		</div>
		<div class="media-body">
			<dl>
				<dt><?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?></dt>
				<dd><?= $row['acuerdo_fvigente_title']; ?></dd>
				<dt><?= Lang::get('acuerdo.columns_title.acuerdo_ffirma'); ?></dt>
				<dd><?= $acuerdo_ffirma_title; ?></dd>
				<dt><?= Lang::get('acuerdo.columns_title.acuerdo_ley'); ?></dt>
				<dd><?= $row['acuerdo_ley']; ?></dd>
				<dt><?= Lang::get('acuerdo.columns_title.acuerdo_decreto'); ?></dt>
				<dd><?= $row['acuerdo_decreto']; ?></dd>
			</dl>
		</div>
		<a href="#" target="_blank"><i class="fa fa-link"></i> Conozca aquí el texto original del acuerdo</a>
	</div>


</div><!-- /modal body -->

<div class="modal-footer margin-top0"><!-- modal footer -->
	<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div><!-- /modal footer -->



