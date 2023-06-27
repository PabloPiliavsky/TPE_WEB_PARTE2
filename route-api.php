<?php 

require_once './libs/Router.php';
require_once './app/controllers/jugadoresApiController.php';


/*--Instancio un nuevo router--*/
$router = new Router();

/*--Defino la tabla de ruteo--*/
$router->addRoute('jugadores', 'GET', 'jugadoresApiController', 'obtenerJugadores');
$router->addRoute('jugadores/:ID', 'GET', 'jugadoresApiController', 'obtenerJugador');
$router->addRoute('jugadores', 'POST', 'jugadoresApiController', 'agregarJugador');
$router->addRoute('jugadores/:ID', 'DELETE', 'jugadoresApiController', 'eliminarJugador');
$router->addRoute('jugadores/:ID', 'PUT', 'jugadoresApiController', 'actualizarJugador');


/*--Rutea--*/
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);


