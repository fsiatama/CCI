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

/*<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">...</div>
    <div role="tabpanel" class="tab-pane" id="profile">...</div>
    <div role="tabpanel" class="tab-pane" id="messages">...</div>
    <div role="tabpanel" class="tab-pane" id="settings">...</div>
  </div>

</div>*/
$htmlAgreementDetItems = '';
$htmlAgreementDetTabs = '';
foreach ($arrAgreementDet as $key => $row) {

	$id = 'agreementDet_'.($key + 1);

	$htmlAgreementDetTabs .= '
		<li><a href="#'. $id .'" data-toggle="tab">'. $id .'</a></li>
	';
	$htmlAgreementDetItems .= '
		<div role="tabpanel" class="tab-pane" id="'. $id .'">
		 Page '. $id .'
		</div>
	';
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

<div class="table-responsive">
	<table class="table table-bordered table-striped">
	  <colgroup>
	    <col class="col-xs-1">
	    <col class="col-xs-7">
	  </colgroup>
	  <tbody>
	    <tr>
	      <th scope="row">
	        <code>.active</code>
	      </th>
	      <td>Applies the hover color to a particular row or cell</td>
	    </tr>
	    <tr>
	      <th scope="row">
	        <code>.success</code>
	      </th>
	      <td>Indicates a successful or positive action</td>
	    </tr>
	    <tr>
	      <th scope="row">
	        <code>.info</code>
	      </th>
	      <td>Indicates a neutral informative change or action</td>
	    </tr>
	    <tr>
	      <th scope="row">
	        <code>.warning</code>
	      </th>
	      <td>Indicates a warning that might need attention</td>
	    </tr>
	    <tr>
	      <th scope="row">
	        <code>.danger</code>
	      </th>
	      <td>Indicates a dangerous or potentially negative action</td>
	    </tr>
	  </tbody>
	</table>
</div>



