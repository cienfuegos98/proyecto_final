<?php

session_start();
require_once "Smarty.class.php";

spl_autoload_register(function($clase) {
    include $clase . '.php';
});

$plantilla = new Smarty();
$plantilla->template_dir = "./template";
$plantilla->compile_dir = "./template_c";

$con = new BD();

if (empty($_SESSION['usuario'])) {
    $loginNav = "<li class='nav-item '>
                    <a class='nav-link' href='index.php'>Login
                        <span class='sr-only'>(current)</span>
                    </a>
                 </li>";
    $foroNav = '';
    $perfil = "<a href='index.php'><img  src='./img/user.png' height='40' width='40' class='rounded-circle hoverable img-responsive'></a>";
} else {

    $foroNav = "<li class='nav-item '>
                    <a class='nav-link' href='comentarios.php'>Foro
                        <span class='sr-only'>(current)</span>
                    </a>
                 </li>";
    $user = $_SESSION['usuario']['nombre'];

    $c = "SELECT * FROM usuarios WHERE user = '$user'";
    $datos = $con->selection($c);

    $uid = $datos[0]['uid'];
    $foto = $datos[0]['foto'];
    $email = $datos[0]['email'];
    $fecha_nac = $datos[0]['fecha_nacimiento'];
    $pass = $datos[0]['pass'];
    $nombreC = $datos[0]['nombre_completo'];
    $direccion = $datos[0]['direccion'];

    $perfil = "<img src='" . $foto . "' height='40' width='40' class='rounded-circle hoverable img-responsive'>";
    $foto_modal = "<img src='" . $foto . "' height='120' width='120'  class='rounded-circle hoverable img-responsive'>";

    $plantilla->assign('email', $email);
    $plantilla->assign('nombre', $user);
    $plantilla->assign('fecha', $fecha_nac);
    $plantilla->assign('direccion', $direccion);
    $plantilla->assign('nombreC', $nombreC);
    $plantilla->assign('pass', $pass);

    $plantilla->assign('foto_modal', $foto_modal);
    $loginNav = "";
    $plantilla->assign('email', $email);
    $plantilla->assign('nombre', $_SESSION['usuario']['nombre']);
}
$plantilla->assign('perfil', $perfil);
$plantilla->assign('foroNav', $foroNav);
$plantilla->assign('loginNav', $loginNav);

if (isset($_POST ['desconectar'])) {
    $loginNav = "<li class = 'nav-item '>
            <a class = 'nav-link' href = 'index.php'>Login
            <span class = 'sr-only'>(current)</span>
            </a>
            </li>";
    $perfil = "<a href='index.php'><img src='./img/user.png' height='40' width='40' class='rounded-circle hoverable img-responsive'></a>";
    $plantilla->assign('perfil', $perfil);
    $plantilla->assign('loginNav', $loginNav);
    $plantilla->assign('foroNav', '');
    $plantilla->assign('nombre', '');
    $plantilla->assign('email', '');
    session_destroy();
}

$c = "SELECT * FROM pabellones";
$datos = $con->selection($c);
//var_dump($datos);
$listadoPabellones = '';

foreach ($datos as $pabellones) {
    $pid = $pabellones['pid'];
    $listadoPabellones .= "
            <div class = 'col-lg-6 col-md-6 mb-lg-0 mb-4 pabellon'>
            <div class = 'card collection-card z-depth-1-half'>
            <div class = 'view zoom'>
            <a href='calendario.php?pid=$pid&nombre=" . $pabellones['nombre'] . "'>
            <img style='width:100%' src = '" . $pabellones['foto'] . "' class='img-fluid ' alt = 'Imagen del" . $pabellones['nombre'] . "' >
            </a>
            <div class = 'stripe dark'>
            <a>
            <div class='expandable fototxt'>
            <h3>" . $pabellones['nombre'] . "</h3>
            <p>" . $pabellones['descripcion'] . "</p>
            </div>
            </a>
            </div>
            </div>
            </div>
            </div>";
}
$con->cerrar();

$plantilla->assign('listadoPabellones', $listadoPabellones);
$plantilla->display("pabellones.tpl");
?>


