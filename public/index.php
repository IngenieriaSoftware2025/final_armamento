<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\MarcaController;
use Controllers\ModeloController;
use Controllers\UsuarioController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);


//Usuarios 


$router->get('/usuarios', [UsuarioController::class, 'renderizarPAgina']);
$router->post('/usuarios/guardarAPI', [UsuarioController::class, 'guardarAPI']);
$router->get('/usuarios/buscarAPI', [UsuarioController::class, 'buscarAPI']);
$router->post('/usuarios/modificarAPI', [UsuarioController::class, 'modificarAPI']);
$router->get('/usuarios/eliminarAPI', [UsuarioController::class, 'eliminarAPI']);
$router->get('/usuarios/rolesAPI', [UsuarioController::class, 'rolesAPI']);
//marcas 
$router->get('/marcas', [MarcaController::class, 'renderizarPAgina']);
$router->post('/marcas/guardarAPI', [MarcaController::class, 'guardarAPI']);
$router->get('/marcas/buscarAPI', [MarcaController::class, 'buscarAPI']);
$router->post('/marcas/modificarAPI', [MarcaController::class, 'modificarAPI']);
$router->get('/marcas/eliminar', [MarcaController::class, 'EliminarAPI']);
$router->get('/marcas/EliminarMarca', [MarcaController::class, 'EliminarMarca']);

///modelos 

$router->get('/modelos', [ModeloController::class, 'renderizarPagina']);
$router->post('/modelos/guardarAPI', [ModeloController::class, 'guardarAPI']);
$router->get('/modelos/buscarAPI', [ModeloController::class, 'buscarAPI']);
$router->post('/modelos/modificarAPI', [ModeloController::class, 'modificarAPI']);
$router->get('/modelos/eliminar', [ModeloController::class, 'EliminarAPI']);
$router->get('/modelos/porMarca', [ModeloController::class, 'modelosPorMarcaAPI']);
$router->get('/marcas/disponibles', [MarcaController::class, 'marcasDisponiblesAPI']);




// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
