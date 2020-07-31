<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <title>ONG - Manejador de proyectos</title>
  <link rel="icon" href="<?=URL_PATH?>/img/logo_skynet.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

  <script type="text/javascript" src="<?=URL_PATH?>/js/jquery-3.3.1.js"></script>
  <script type="text/javascript" src="<?=URL_PATH?>/js/bootstrap.bundle.js"></script>
  <script type="text/javascript" src="<?=URL_PATH?>/js/FuncionesComunes.js"></script>
	<script type="text/javascript" src="<?=URL_PATH?>/js/LoginAdministrador.js"></script>

  <link rel="stylesheet" type="text/css" href="<?=URL_PATH?>/css/customBootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="<?=URL_PATH?>/css/estilo.css"/>
  <link rel="stylesheet" type="text/css" href="<?=URL_PATH?>/css/all.min.css"/>
  <style>
    .BasicLayout{
      min-height: 100vh;
      background: white;
      padding: 1rem;

      display: flex;
      justify-content: center;
      align-items: center;

      box-shadow: 0 -4px #1890FF inset;
      background: linear-gradient(-45deg, #22303a 50%, #2a3b47 50%);
    }
    .card{
      border: 0;
    }
    .LoginContainer{
      max-width: 400px;
      width: 100%;
      box-shadow: 0 1px 8px 0 rgba(0,0,0,0.08);
    }
    .LoginTitle{
      font-size: 2rem;
      margin: 2rem auto;
    }
  </style>
</head>
<body>
  <div class="BasicLayout">
    <div class="LoginContainer">
      <div class="card">
        <div class="card-body text-center pb-5">
          <h5 class="LoginTitle">LOGIN</h5>
          <form class="login-form" action="<?=URL_PATH?>/Inicio/Login" method="post">
            <?php if (isset($error)): ?>
              <div class="alert alert-danger" role="alert">
                <?=$error?>
              </div>
            <?php endif;?>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-at"></i></span>
            </div>
            <input name="correo" type="email" class="form-control" placeholder="Correo electrónico" required="required" value="">
          </div>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>
            </div>
            <input name="password" type="password" class="form-control" placeholder="Contraseña" required="required" value="">
          </div>

          <div class="form-group">
            <input type="submit" class="btn btn-primary btn-block btn-lg" value="Acceder">
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<footer>
</footer>
</html>