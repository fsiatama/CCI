<?php 

$acuerdo_ffirma_title = ( empty($row['acuerdo_ffirma_title']) ) ? '' : $row['acuerdo_ffirma_title'] ;

$partner = ( empty($row['mercado_nombre']) ) ? $row['pais'] : $row['mercado_nombre'] ;

$flag = '';

if (empty($row['acuerdo_mercado_id'])) {
	$flag = 'img/flags/64/'.$row['pais_iata'].'.png';
} else {
	$flag = 'img/flags/markets/'.$row['mercado_bandera'];
}
$flag = ( is_file(PATH_RAIZ.$flag) && file_exists(PATH_RAIZ.$flag) ) ? URL_RAIZ.$flag : 'holder.js/64x64/sky/size:7/text:' . $partner ;

$acuerdo_url = '';
if ( ! empty($row['acuerdo_url']) ) {
	$acuerdo_url = '
		<a href="' . $row['acuerdo_url'] . '" target="_blank"><i class="fa fa-link"></i> Conozca aquí el texto original del acuerdo</a>
	';
}

$acuerdo_ffirma = '';
if ( ! empty($row['acuerdo_ffirma']) ) {
	$acuerdo_ffirma = '
		<dt>' . Lang::get('acuerdo.columns_title.acuerdo_ffirma') . '</dt>
		<dd>' . $acuerdo_ffirma_title . '</dd>
	';
}

$acuerdo_ley = '';
if ( ! empty($row['acuerdo_ley']) ) {
	$acuerdo_ley = '
		<dt>' . Lang::get('acuerdo.columns_title.acuerdo_ley') . '</dt>
		<dd>' . $row['acuerdo_ley'] . '</dd>
	';
}

$acuerdo_decreto = '';
if ( ! empty($row['acuerdo_decreto']) ) {
	$acuerdo_decreto = '
		<dt>' . Lang::get('acuerdo.columns_title.acuerdo_decreto') . '</dt>
		<dd>' . $row['acuerdo_decreto'] . '</dd>
	';
}

?>


<div class="modal-header"><!-- modal header -->
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h5 class="modal-title" id="agreementModalLabel"><i class="fa fa-globe"></i>  <?= $partner; ?></h5>
</div><!-- /modal header -->

<div class="modal-body"><!-- modal body -->

	<div class="media">
		<div class="media-left">
			<img class="media-object" src="<?= $flag; ?>" alt="<?= $partner; ?>">
		</div>
		<div class="media-body">
			<?= $row['acuerdo_descripcion']; ?>
			<dl>
				<dt><?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?></dt>
				<dd><?= $row['acuerdo_fvigente_title']; ?></dd>
				
				<?= $acuerdo_ffirma; ?>

				<?= $acuerdo_ley; ?>

				<?= $acuerdo_decreto; ?>
				
			</dl>

			<?= $acuerdo_url; ?>
		</div>
		
	</div>


</div><!-- /modal body -->

<div class="modal-footer margin-top0"><!-- modal footer -->
	<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div><!-- /modal footer -->



