<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\AsignacionesController;
use Controllers\EstadisticaController;
use Controllers\MapasController;
use Controllers\MarcaController;
use Controllers\ModeloController;
use Controllers\UsuarioController;


$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);
$router->get('/inicio', [AppController::class,'inicio']);
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

//modelos 

$router->get('/modelos', [ModeloController::class, 'renderizarPagina']);
$router->post('/modelos/guardarAPI', [ModeloController::class, 'guardarAPI']);
$router->get('/modelos/buscarAPI', [ModeloController::class, 'buscarAPI']);
$router->post('/modelos/modificarAPI', [ModeloController::class, 'modificarAPI']);
$router->get('/modelos/eliminarAPI', [ModeloController::class, 'EliminarAPI']);
$router->get('/modelos/marcasAPI', [ModeloController::class, 'marcasAPI']);


// Luego agrega las rutas:
$router->get('/asignaciones', [AsignacionesController::class, 'renderizarPagina']);
$router->post('/asignaciones/guardarAPI', [AsignacionesController::class, 'guardarAPI']);
$router->get('/asignaciones/buscarAPI', [AsignacionesController::class, 'buscarAPI']);
$router->post('/asignaciones/modificarAPI', [AsignacionesController::class, 'modificarAPI']);
$router->get('/asignaciones/eliminarAPI', [AsignacionesController::class, 'eliminarAPI']);
$router->get('/asignaciones/usuariosAPI', [AsignacionesController::class, 'usuariosAPI']);
$router->get('/asignaciones/modelosAPI', [AsignacionesController::class, 'modelosAPI']);


//estadisticas 
$router->get('/estadisticas', [EstadisticaController::class, 'renderizarPagina']);
$router->get('/estadisticas/buscarAPI', [EstadisticaController::class, 'buscarAPI']);
$router->get('/estadisticas/buscarClientesAPI', [EstadisticaController::class, 'buscarClientesAPI']);
$router->get('/estadisticas/buscarVentasMesAPI', [EstadisticaController::class, 'buscarVentasMesAPI']);
$router->get('/estadisticas/buscarMarcasAPI', [EstadisticaController::class, 'buscarMarcasAPI']);
$router->get('/estadisticas/buscarTrabajadoresAPI', [EstadisticaController::class, 'buscarTrabajadoresAPI']);
$router->get('/estadisticas/buscarUsuariosAPI', [EstadisticaController::class, 'buscarUsuariosAPI']);

$router->get('/asignaciones/test', [AsignacionesController::class, 'testAPI']);

//mapas
$router->get('/mapas', [MapasController::class, 'renderizarPagina']);


//login 
$router->get('/login', [AppController::class,'index']);
$router->get('/logout', [AppController::class,'logout']);
$router->post('/API/login', [AppController::class,'login']);
$router->get('/API/logout', [AppController::class,'logout']);
$router->post('/hashear', [AppController::class, 'hashearPassword']);
$router->post('/actualizarPasswordsExistentes', [AppController::class, 'actualizarPasswordsExistentes']);





// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
