<!DOCTYPE html>
<html lang="es">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>ONG - Proyectos</title>
  <link rel="icon" href="<?= URL_PATH ?>/img/logo_skynet.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

  <script type="text/javascript" src="<?= URL_PATH ?>/js/jquery-3.3.1.js"></script>
  <script type="text/javascript" src="<?= URL_PATH ?>/js/bootstrap.bundle.js"></script>
  <script type="text/javascript" src="<?= URL_PATH ?>/js/jquery.blockUI.min.js"></script>
  <script type="text/javascript" src="<?= URL_PATH ?>/js/FuncionesComunes.js"></script>
  <script type="text/javascript" src="<?= URL_PATH ?>/js/datePicker/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="<?= URL_PATH ?>/js/chosen.jquery.min.js"></script>
  <script type="text/javascript" src="<?= URL_PATH ?>/js/Admin.js"></script>

  <link rel="stylesheet" type="text/css" href="<?= URL_PATH ?>/css/customBootstrap.css"/>
  <link rel="stylesheet" type="text/css" href="<?= URL_PATH ?>/css/estilo.css"/>
  <link rel="stylesheet" type="text/css" href="<?= URL_PATH ?>/css/admin.css"/>
  <link rel="stylesheet" type="text/css" href="<?= URL_PATH ?>/css/all.min.css"/>
  <link rel="stylesheet" type="text/css" href="<?= URL_PATH ?>/js/datePicker/css/bootstrap-datepicker3.css"/>
  <link rel="stylesheet" type="text/css" href="<?= URL_PATH ?>/css/chosen.min.css"/>
  <script>
    var URL_PATH = '<?= URL_PATH ?>';
  </script>
</head>
<body>


  <div class="AdminLayout AdminLayoutL1" id="AdminLayout">
    <div class="AdminLayout-header">
        <header class="navbar navbar-expand-sm AdminHeader">
            <div id="AdminSidebar-toggle" class="AdminHeader-action">
                <i class="fas fa-bars"></i>
            </div>
            <div class="ml-auto d-flex align-items-center">
              <div class="AdminHeader-action2">
                <strong>Usuario : </strong>
                <div><?php echo $_SESSION['usuario']; ?></div>
              </div>
              <div class="AdminHeader-action2">
                <strong>Cargo : </strong>
                <div><?php echo $_SESSION['perfil'] ?></div>
              </div>
              <div class="AdminHeader-action2 logout">
                <form method="post" action="<?= URL_PATH ?>/Inicio/Salir">
                  <button type="submit"><i class="fas fa-power-off"></i></button>
                </form>
              </div>
            </div>
        </header>
    </div>
    <div class="AdminLayout-main">
      <?php echo $contenido ?>
    </div>
    <aside class="AdminLayout-sidebar AdminSidebar-wrapper" id="AdminSidebar">
        <div class="AdminSidebar-content">
            <div class="AdminSidebar-brand">
                <a href="<?=URL_PATH?>/admin">
                    <img src="<?=URL_PATH . '/img/logo_skynet.png'?>" alt="logo" width="48px" class="mr-2">
                    <span class="AdminSidebar-brandName">Skynet - ONG<span>Sistema para ongs</span></span>
                </a>
            </div>
            <div class="AdminSidebar-header">

            </div>
            <ul class="AdminSidebar-menu">
                <li class="AdminSidebar-title">General</li>

                <li class="AdminSidebar-dropdown" name="menuProyecto">
                    <a href="#">
                      <i class="fas fa-project-diagram mr-2"></i>
                        <span>Proyectos</span>
                        <i class="iconAction">
                            <i class="fas fa-chevron-down"></i>
                        </i>
                    </a>
                    <ul class="AdminSidebar-submenu">
                      <li name="menuVerProyecto">
                          <a href="<?=URL_PATH . '/ProyectoLista/ListarProyecto'?>">
                            <i class="fas fa-list mr-2"></i>Ver Proyectos
                          </a>
                      </li>
                      <li name="menuCrearProyecto">
                          <a href="<?=URL_PATH . '/ProyectoLista/ProyectoNuevo'?>">
                          <i class="fas fa-folder-plus mr-2"></i>Crear Proyecto
                          </a>
                      </li>
                    </ul>
                </li>

                <li class="AdminSidebar-dropdown" name="menuUsuario">
                    <a href="<?=URL_PATH . '/Usuario/Usuario'?>">
                        <i class="far fa-user-circle mr-2"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li class="AdminSidebar-dropdown" name="menuParticipante">
                    <a href="#">
                      <i class="fas fa-user-friends mr-2"></i>
                        <span>Participantes</span>
                        <i class="iconAction">
                            <i class="fas fa-chevron-down"></i>
                        </i>
                    </a>
                    <ul class="AdminSidebar-submenu">
                      <li>
                          <a href="<?=URL_PATH . '/Persona/Persona'?>">
                            <i class="fas fa-list mr-2"></i>Personas
                          </a>
                      </li>
                      <li>
                          <a href="<?=URL_PATH . '/Familia/Familia'?>">
                            <i class="fas fa-folder-plus mr-2"></i>Familias
                          </a>
                      </li>
                    </ul>
                </li>

                <li class="AdminSidebar-title">Reporte</li>

                <li class="AdminSidebar-dropdown" name="menuReporte">
                    <a href="#">
                      <i class="far fa-chart-bar mr-2"></i>
                        <span>Reportes</span>
                        <i class="iconAction">
                            <i class="fas fa-chevron-down"></i>
                        </i>
                    </a>
                    <ul class="AdminSidebar-submenu">
                      <li>
                          <a href="<?=URL_PATH . '/Reporte/Avance'?>">
                            <i class="fas fa-flag-checkered mr-2"></i>Avance
                          </a>
                      </li>
                      <li>
                          <a href="<?=URL_PATH . '/Reporte/AvanceAnualActividad'?>">
                            <i class="fas fa-flag-checkered mr-2"></i>Avance de Actividades
                          </a>
                      </li>
                      <li>
                          <a href="<?=URL_PATH . '/Reporte/Medicion'?>">
                            <i class="fas fa-chart-line mr-2"></i>Medición
                          </a>
                      </li>
                      <li>
                          <a href="<?=URL_PATH . '/Reporte/Participacion'?>">
                            <i class="fas fa-tools mr-2"></i>Participación
                          </a>
                      </li>
                      <li>
                          <a href="<?=URL_PATH . '/Reporte/ParticipanteComunidad'?>">
                            <i class="fas fa-users mr-2"></i>Participantes y comunidades
                          </a>
                      </li>
                      <li>
                          <a href="<?=URL_PATH . '/Reporte/VistaReporteActividad'?>">
                            <i class="fas fa-hammer mr-2"></i>Actividad
                          </a>
                      </li>
                    </ul>
                </li>

                <li class="AdminSidebar-dropdown" name="menuComunidad">
                    <a href="<?=URL_PATH . '/Reporte/Comunidad'?>">
                        <i class="far fa-chart-bar mr-2"></i>
                        <span>Comunidades</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
  </div>

  <script type="text/javascript">
    var accesos = JSON.parse('<?php echo json_encode($accesos); ?>');

    function ActualizarAcceso() {
      for (var i = 0; i < accesos.length; i++) {
        $('[name="'+accesos[i].permiso+'"').removeClass('d-none');
      }
    }

    ActualizarAcceso();
  </script>
</body>
<footer>
</footer>
</html>