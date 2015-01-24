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

        <link rel="stylesheet" href="/css/normalize.css" type="text/css"/>
        <link rel="stylesheet" href="/css/bootstrap.css" type="text/css">
        <link rel="stylesheet" href="/css/bootstrap-theme.min.css" type="text/css">

        <link rel="stylesheet" href="/css/main.css">
        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.1.min.js"><\/script>')</script>

        <!--[if lt IE 7]>
                <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Fixed navbar -->
        
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= URL_RAIZ ?>">
                        <!--<img src="<?= URL_RAIZ ?>img/logo.png" />-->
                        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    </a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menú<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?= URL_RAIZ ?>Home/GuiaBasicaParaExportar">1. Guía básica para exportar </a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/InformacionGeneralTLC">2. Información General de los TLC</a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/RequisitosIngresoColombia">3. Condiciones y requisitos de ingreso para Colombia por producto</a></li>
                                <li><a href="#">- Cronograma de desgravación y contingentes otorgados</a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/ProtocolosExportacion">4. Protocolos de exportación</a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/RequisitosSanitariosFitosanitarios">5. Requisitos sanitarios y fitosanitarios</a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/LosgisticaTransporte">6. Logística de transporte</a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/ComercioAgropecuarioColombia">7. Comercio agropecuario de Colombia</a></li>
                                <li><a href="#">- Colombia al Mundo</a></li>
                                <li><a href="#">- Principales destinos y orígenes</a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/ColombiaPorAcuerdoComercial">8. Colombia Por Acuerdo comercial</a></li>
                                <li><a href="#">- Composición de las exportaciones e importaciones a 6 dígitos por socio comercial</a></li>
                                <li><a href="#">- Utilización de contingentes</a></li>
                                <li><a href="#">- Tarifas arancelarias y Precios Implícitos por Partida arancelaria (producto)</a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/ComercioAgropecuarioMundial">9. Comercio agropecuario mundial por producto</a></li>
                                <li><a href="<?= URL_RAIZ ?>Home/PosicionamientoProductos">10. Posicionamiento y dinamismo de los productos</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a class="navbar-brand" href="<?= URL_RAIZ ?>Home/Login">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                <span>Ingresar</span>
                            </a></li>
                        <li><a class="navbar-brand" href="#">
                                <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                <span>Acerca de</span>
                            </a></li>
                        <li><a class="navbar-brand" href="#">
                                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                                <span>Contacto</span>
                            </a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        
        <div class="container main-block">
            <?= $tpl_content; ?>
        </div><!-- /container -->

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
        <script src="/js/vendor/bootstrap.min.js"></script>
        <script src="/js/plugins.js"></script>
        <script src="/js/main.js"></script>		
    </body>
</html>
