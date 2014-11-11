<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="google" content="notranslate" />

		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/main.css">
		<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	</head>
    <body>
		<!--[if lt IE 7]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
    
		<hr>
		<div class="container">
			<?= $tpl_content; ?>
		</div>
		<!-- /container -->
		<div class="footer">
			<hr>
			<div class="container">
				<div class="row">
					<p>&copy; Company 2014</p>
				</div>
			</div>
		</div>
		<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">

					<div class="modal-header"><!-- modal header -->
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="errorModalLabel"><i class="fa fa-warning"></i>  Error!</h4>
					</div><!-- /modal header -->

					<!-- modal body -->
					<div class="modal-body">
						<div id="modal-error-msg" class="alert alert-danger margin-bottom0">
						</div>
					</div>
					<!-- /modal body -->

					<div class="modal-footer margin-top0"><!-- modal footer -->
						<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div><!-- /modal footer -->

				</div>
			</div>
		</div>
		<div class="modal fade" id="sucessModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">

					<div class="modal-header"><!-- modal header -->
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="successModalLabel"><i class="fa fa-check"></i>  Success!</h4>
					</div><!-- /modal header -->

					<!-- modal body -->
					<div class="modal-body">
						<div id="modal-success-msg" class="alert alert-success margin-bottom0">
						</div>
					</div>
					<!-- /modal body -->

					<div class="modal-footer margin-top0"><!-- modal footer -->
						<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div><!-- /modal footer -->

				</div>
			</div>
		</div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		<script src="js/vendor/bootstrap.min.js"></script>
		<script src="js/plugins.js"></script>
		<script src="js/main.js"></script>		
    </body>
</html>
