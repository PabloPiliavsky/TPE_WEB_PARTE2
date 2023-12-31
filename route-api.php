<?php 
require_once './libs/Router.php';
require_once './app/controllers/jugadoresApiController.php';
require_once './app/controllers/paisesApiController.php';
require_once './app/controllers/usuarioApiController.php';

/*--Instancia un nuevo router--*/
$router = new Router();

/*--Define la tabla de ruteo--*/
$router->addRoute('jugadores', 'GET', 'jugadoresApiController', 'obtenerJugadores');
$router->addRoute('jugadores/:ID', 'GET', 'jugadoresApiController', 'obtenerJugador');
$router->addRoute('jugadores', 'POST', 'jugadoresApiController', 'agregarJugador');
$router->addRoute('jugadores/:ID', 'DELETE', 'jugadoresApiController', 'eliminarJugador');
$router->addRoute('jugadores/:ID', 'PUT', 'jugadoresApiController', 'actualizarJugador');

$router->addRoute('paises', 'GET', 'paisesApiController', 'obtenerPaises');
$router->addRoute('paises/:ID', 'GET', 'paisesApiController', 'obtenerPais');
$router->addRoute('paises', 'POST', 'paisesApiController', 'agregarPais');
$router->addRoute('paises/:ID', 'DELETE', 'paisesApiController', 'eliminarPais');
$router->addRoute('paises/:ID', 'PUT', 'paisesApiController', 'actualizarPais');


$router->addRoute('usuario','POST','usuarioApiController','login');


/*--Rutea--*/
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);


