<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\AsignacionArmamentosController;
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



// Página principal de asignación de armamentos
$router->get('/asignaciones', [AsignacionArmamentosController::class, 'renderizarPagina']);
$router->get('/asignaciones/buscarUsuariosAPI', [AsignacionArmamentosController::class, 'buscarUsuariosAPI']);
$router->get('/asignaciones/buscarArmamentosAPI', [AsignacionArmamentosController::class, 'buscarArmamentosAPI']);

// APIs para operaciones CRUD
$router->post('/asignaciones/guardarAPI', [AsignacionArmamentosController::class, 'guardarAPI']);
$router->get('/asignaciones/buscarAPI', [AsignacionArmamentosController::class, 'buscarAPI']);
$router->post('/asignaciones/modificarAPI', [AsignacionArmamentosController::class, 'modificarAPI']);
$router->get('/asignaciones/retirar', [AsignacionArmamentosController::class, 'retirarAPI']);
$router->post('/asignaciones/retirar', [AsignacionArmamentosController::class, 'retirarAPI']);

// APIs para consultas específicas
$router->get('/asignaciones/estadisticasAPI', [AsignacionArmamentosController::class, 'estadisticasAPI']);
$router->get('/asignaciones/verificarAsignacionAPI', [AsignacionArmamentosController::class, 'verificarAsignacionAPI']);
$router->get('/asignaciones/obtenerPorUsuarioAPI', [AsignacionArmamentosController::class, 'obtenerPorUsuarioAPI']);



//AGREGAR ESTAS RUTAS A TU ARCHIVO DE RUTAS (después de las rutas de estadísticas existentes)

//Estadísticas específicas de armamentos
$router->get('/estadisticas/buscarTiposArmamentosAPI', [EstadisticaController::class, 'buscarTiposArmamentosAPI']);
$router->get('/estadisticas/buscarUsuariosArmamentosAPI', [EstadisticaController::class, 'buscarUsuariosArmamentosAPI']);
$router->get('/estadisticas/buscarMarcasArmamentosAPI', [EstadisticaController::class, 'buscarMarcasArmamentosAPI']);
$router->get('/estadisticas/buscarModelosArmamentosAPI', [EstadisticaController::class, 'buscarModelosArmamentosAPI']);
$router->get('/estadisticas/buscarAsignacionesMesAPI', [EstadisticaController::class, 'buscarAsignacionesMesAPI']);
$router->get('/estadisticas/buscarEstadisticasGeneralesAPI', [EstadisticaController::class, 'buscarEstadisticasGeneralesAPI']);
$router->get('/estadisticas/buscarTopAsignadorasAPI', [EstadisticaController::class, 'buscarTopAsignadorasAPI']);

//Ruta adicional para la página específica de estadísticas de armamentos (opcional)
$router->get('/estadisticas/armamentos', [EstadisticaController::class, 'renderizarPaginaArmamentos']);



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
