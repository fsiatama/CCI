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
                    <a class="navbar-brand" href="#">Project name</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="../navbar/">Default</a></li>
                        <li><a href="../navbar-static-top/">Static top</a></li>
                        <li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <hr>
        <div class="container">
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
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.1.min.js"><\/script>')</script>
        <script src="/js/vendor/bootstrap.min.js"></script>
        <script src="/js/plugins.js"></script>
        <script src="/js/main.js"></script>		
    </body>
</html>
