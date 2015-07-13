<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <!--<img src="<?= URL_RAIZ ?>img/logo-330_106.png" class="img-responsive center-block" alt="Responsive image">-->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong class="">Inicio de sesión</strong>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" id="loginForm" action="<?= URL_RAIZ ?>auth/login">
                        <div class="form-group">
                            <label for="inputEmail" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Correo Electrónico" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-sm-3 control-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" required="">
                            </div>
                        </div>
                        <div class="form-group last">
                            <div class="col-sm-offset-6 col-sm-6">
                                <button id="loginFormSubmit" type="submit" class="btn btn-green btn-sm btn-block">Ingresar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <div class="panel-footer">
                        <a href="#" class="">Olvido su contraseña?</a>
                </div> -->
            </div>
        </div>
    </div>
</div>
