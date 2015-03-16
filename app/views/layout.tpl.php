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

		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800" rel="stylesheet" type="text/css" />

		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?= URL_RAIZ; ?>css/layout.css" />

		<script src="<?= URL_RAIZ; ?>js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	</head>
	<body>
		<!--[if lt IE 7]>
				<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->

		<header id="header">

			<nav class="navbar navbar-inverse" role="navigation"><!-- add "white" class for white nav bar -->
				<div class="container">

					<!-- Mobile Menu Button -->
					<button id="mobileMenu" class="fa fa-bars" type="button" data-toggle="collapse" data-target=".navbar-collapse"></button>

					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<a class="navbar-brand" href="<?= URL_RAIZ; ?>">
							<img class="img-responsive visible-xs visible-sm visible-md " src="<?= URL_RAIZ; ?>img/logo_xs.png" alt="www.minagricultura.gov.co" width="175" height="60" />
							<img class="img-responsive hidden-xs hidden-sm hidden-md" src="<?= URL_RAIZ; ?>img/logo_home_new.png" alt="www.minagricultura.gov.co" width="265" height="80" />
						</a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

						<ul class="nav navbar-nav navbar-right">
							<li class="active"><a href="<?= URL_RAIZ; ?>">Inicio</a></li>
							<li><a href="<?= URL_RAIZ ?>informacion-general">Información General tlcs</a></li>
							<li><a href="<?= URL_RAIZ ?>guia-basica-exportar">Guia Básica Exportar</a></li>
							<li><a href="<?= URL_RAIZ ?>herramientas">Herramientas</a></li>
							<li><a href="<?= URL_RAIZ ?>auth">Inicio de Sesión</a></li>
						</ul>

					</div><!-- /.navbar-collapse -->

				</div>
			</nav>

		</header>

		<!-- Fixed navbar -->
		
		<!-- MIDDLE -->
		<section id="middle">
			<?= $tpl_content; ?>
		</section>
		<!-- /MIDDLE -->


		<!-- FOOTER -->
		<footer>
			<div class="container">
				<p class="copyright">
					Ministerio de Agricultura y Desarrollo Rural. República de Colombia. Bogotá D.C<br />
					2014 &copy; All Rights Reserved.
				</p>
			</div>
		</footer>
		<!-- /FOOTER -->


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

					<div class="modal-body"><!-- modal body -->
						<div id="modal-success-msg" class="alert alert-success margin-bottom0">
						</div>
					</div><!-- /modal body -->

					<div class="modal-footer margin-top0"><!-- modal footer -->
						<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
					</div><!-- /modal footer -->

				</div>
			</div>
		</div>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?= URL_RAIZ; ?>js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.5.2/holder.min.js"></script>
		<script src="<?= URL_RAIZ; ?>js/plugins.js"></script>
		<script src="<?= URL_RAIZ; ?>js/main.js"></script>
	</body>
</html>
