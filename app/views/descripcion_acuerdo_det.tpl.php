<?php 

$acuerdo_ffirma_title = ( empty($rowAgreement['acuerdo_ffirma_title']) ) ? '' : $rowAgreement['acuerdo_ffirma_title'] ;

$partner = ( empty($rowAgreement['mercado_nombre']) ) ? $rowAgreement['pais'] : $rowAgreement['mercado_nombre'] ;

$flag = '';

if (empty($rowAgreement['acuerdo_mercado_id'])) {
	$flag = 'img/flags/64/'.$rowAgreement['pais_iata'].'.png';
} else {
	$flag = 'img/flags/markets/'.$rowAgreement['mercado_bandera'];
}

$flag = ( is_file(PATH_RAIZ.$flag) && file_exists(PATH_RAIZ.$flag) ) ? URL_RAIZ.$flag : 'holder.js/64x64/sky/size:7/text:' . $partner ;

$acuerdo_url = '';
if ( ! empty($rowAgreement['acuerdo_url']) ) {
	$acuerdo_url = '
		<a href="' . $rowAgreement['acuerdo_url'] . '" target="_blank"><i class="fa fa-link"></i> Conozca aquí el texto original del acuerdo</a>
	';
}

$acuerdo_ffirma = '';
if ( ! empty($rowAgreement['acuerdo_ffirma']) ) {
	$acuerdo_ffirma = '
		<dt>' . Lang::get('acuerdo.columns_title.acuerdo_ffirma') . '</dt>
		<dd>' . $acuerdo_ffirma_title . '</dd>
	';
}

$acuerdo_ley = '';
if ( ! empty($rowAgreement['acuerdo_ley']) ) {
	$acuerdo_ley = '
		<dt>' . Lang::get('acuerdo.columns_title.acuerdo_ley') . '</dt>
		<dd>' . $rowAgreement['acuerdo_ley'] . '</dd>
	';
}

$acuerdo_decreto = '';
if ( ! empty($rowAgreement['acuerdo_decreto']) ) {
	$acuerdo_decreto = '
		<dt>' . Lang::get('acuerdo.columns_title.acuerdo_decreto') . '</dt>
		<dd>' . $rowAgreement['acuerdo_decreto'] . '</dd>
	';
}

$htmlAgreementDetItems = '';
$htmlAgreementDetTabs = '';

//var_dump($arrAgreementDet);
foreach ($arrAgreementDet as $key => $row) {

	$id = 'agreementDet_'.($key + 1);

	$htmlAgreementDetTabs .= '
		<li><a href="#'. $id .'" data-toggle="tab" data-key="'.$row['acuerdo_det_id'].'" >'. $id .'</a></li>
	';

	$htmlProducts = '<dl>';
	foreach ($row['productsData'] as $product) {

		$htmlProducts .= '<dt>'.$product['id_posicion'].'</dt></dd>'.$product['posicion'].'</dd>';
	}
	$htmlProducts .= '</dl>';

	$htmlAgreementDetItems .= '
		<div role="tabpanel" class="tab-pane" id="'. $id .'">
		 <div class="table-responsive">
		 	<table class="table table-bordered table-striped">
		 	  <colgroup>
		 	    <col class="col-xs-2">
		 	    <col class="col-xs-6">
		 	  </colgroup>
		 	  <tbody>
		 	    <tr>
		 	      <th scope="row">
		 	        <code>' . Lang::get('acuerdo_det.columns_title.acuerdo_det_productos') . '</code>
		 	      </th>
		 	      <td class="fsize11">' . $htmlProducts . '</td>
		 	    </tr>
		 	    <tr>
		 	      <th scope="row">
		 	        <code>' . Lang::get('acuerdo_det.columns_title.acuerdo_det_productos_desc') . '</code>
		 	      </th>
		 	      <td class="fsize11">' . $row['acuerdo_det_productos_desc'] . '</td>
		 	    </tr>
		 	    <tr>
		 	      <th scope="row">
		 	        <code>' . Lang::get('acuerdo_det.columns_title.acuerdo_det_arancel_base') . '</code>
		 	      </th>
		 	      <td class="fsize11">' . $row['acuerdo_det_arancel_base'] . '%</td>
		 	    </tr>
		 	    <tr>
		 	      <th scope="row">
		 	        <code>' . Lang::get('acuerdo_det.columns_title.acuerdo_det_nperiodos') . '</code>
		 	      </th>
		 	      <td class="fsize11">' . $row['acuerdo_det_nperiodos'] . '</td>
		 	    </tr>
		 	  </tbody>
		 	</table>
		 </div>
	';

	$rowContingente = $row['rowContingente'];


	$htmlAgreementDetItems .= '
	<div class="row">
		<div class="col-sm-12">
			<p>'.$rowContingente['contingente_desc'].'<p>
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="quotaDatasource">
				<thead>
					<th>Año</th>
					<th>'.Lang::get('contingente_det.peso_contingente').'</th>
					<th>'.Lang::get('desgravacion_det.columns_title.desgravacion_det_tasa').'</th>
				</thead>
				<tbody>
	';

	$zeroQuota = false;
	$zeroDuty  = false;

	foreach ($row['arrDetail'] as $year => $rowDet) {
		$quota     = ($rowDet['quota'] == 0) ? Lang::get('contingente_det.peso_ilimitado') : number_format($rowDet['quota'], 2) ;

		if ( !$zeroQuota && !$zeroDuty ) {
			$htmlAgreementDetItems .= '
			<tr>
				<td>'.$rowDet['year'].'</td>
				<td class="text-right">'.$quota.'</td>
				<td class="text-right">'.number_format($rowDet['duty'], 2).'%</td>
			</tr>
			';
		}
		$zeroQuota = ($rowDet['quota'] == 0) ? true : false;
		$zeroDuty  = ($rowDet['duty'] == 0) ? true : false;
	}

	$htmlAgreementDetItems .= '
				</tbody>
			</table>
		</div>
	</div>
	';


	$htmlAgreementDetItems .= '</div>';
}

$htmlAgreementDet = '
<div role="tabpanel">
	<ul class="nav hide nav-tabs" id="agreementDetTabs">
		'. $htmlAgreementDetTabs .'
	</ul>
	<div class="tab-content">
		'. $htmlAgreementDetItems .'
	</div>
</div>
';


?>


<div class="media">
	<div class="media-left">
		<img class="media-object" src="<?= $flag; ?>" alt="<?= $partner; ?>">
	</div>
	<div class="media-body">
		<?= $rowAgreement['acuerdo_descripcion']; ?>
		<dl>
			<dt><?= Lang::get('acuerdo.columns_title.acuerdo_fvigente'); ?></dt>
			<dd><?= $rowAgreement['acuerdo_fvigente_title']; ?></dd>
			
			<?= $acuerdo_ffirma; ?>

			<?= $acuerdo_ley; ?>

			<?= $acuerdo_decreto; ?>
			
		</dl>

		<?= $acuerdo_url; ?>
	</div>
	
</div>

<hr class="half-margins">
<ul id="pagination" class="pagination-sm"></ul>

<?= $htmlAgreementDet; ?>








