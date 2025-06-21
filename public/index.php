<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\AsignacionMarcasController;
use Controllers\EstadisticaController;
use Controllers\HistorialController;
use Controllers\MapasController;
use Controllers\MarcaController;
use Controllers\ModeloController;
use Controllers\RolController;
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
$router->get('/usuarios/EliminarAPI', [UsuarioController::class, 'EliminarAPI']);
$router->get('/usuarios/rolesAPI', [UsuarioController::class, 'rolesAPI']);
$router->get('/usuarios/usuariosAPI', [UsuarioController::class, 'usuariosAPI']);

$router->get('/marcas/reactivarAPI', [MarcaController::class, 'ReactivarAPI']);


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


//AGREGAR ESTAS RUTAS A TU ARCHIVO DE RUTAS (después de las rutas de estadísticas existentes)

//Estadísticas específicas de armamentos
$router->get('/estadisticas/buscarModelosAPI', [EstadisticaController::class, 'buscarModelosAPI']);
$router->get('/estadisticas/buscarMarcasAPI', [EstadisticaController::class, 'buscarMarcasAPI']);
$router->get('/estadisticas/buscarUsuariosAPI', [EstadisticaController::class, 'buscarUsuariosAPI']);
$router->get('/estadisticas/buscarAsignacionesAPI', [EstadisticaController::class, 'buscarAsignacionesAPI']);
$router->get('/estadisticas/buscarEstadisticasRolAPI', [EstadisticaController::class, 'buscarEstadisticasRolAPI']);
$router->get('/estadisticas/buscarResumenGeneralAPI', [EstadisticaController::class, 'buscarResumenGeneralAPI']);

//Ruta principal de estadísticas
$router->get('/estadisticas', [EstadisticaController::class, 'renderizarPagina']);


//marcas 
$router->get('/marcas', [MarcaController::class, 'renderizarPAgina']);
$router->post('/marcas/guardarAPI', [MarcaController::class, 'guardarAPI']);
$router->get('/marcas/buscarAPI', [MarcaController::class, 'buscarAPI']);
$router->post('/marcas/modificarAPI', [MarcaController::class, 'modificarAPI']);
$router->get('/marcas/eliminar', [MarcaController::class, 'EliminarAPI']);
$router->get('/marcas/EliminarMarca', [MarcaController::class, 'EliminarMarca']);


//mapas
$router->get('/mapas', [MapasController::class, 'renderizarPagina']);


//login 
$router->get('/login', [AppController::class,'index']);
$router->get('/logout', [AppController::class,'logout']);
$router->post('/API/login', [AppController::class,'login']);
$router->get('/API/logout', [AppController::class,'logout']);
$router->post('/hashear', [AppController::class, 'hashearPassword']);
$router->post('/actualizarPasswordsExistentes', [AppController::class, 'actualizarPasswordsExistentes']);


//ASIGNACIÓN DE MARCAS
$router->get('/asignacionmarcas', [AsignacionMarcasController::class, 'renderizarPAgina']);
$router->post('/asignacionmarcas/guardarAPI', [AsignacionMarcasController::class, 'guardarAPI']);
$router->get('/asignacionmarcas/buscarAPI', [AsignacionMarcasController::class, 'buscarAPI']);
$router->post('/asignacionmarcas/modificarAPI', [AsignacionMarcasController::class, 'modificarAPI']);
$router->get('/asignacionmarcas/eliminar', [AsignacionMarcasController::class, 'EliminarAPI']);
$router->get('/asignacionmarcas/usuariosAPI', [AsignacionMarcasController::class, 'usuariosAPI']);
$router->get('/asignacionmarcas/marcasAPI', [AsignacionMarcasController::class, 'marcasAPI']);


// Para usuarios - AGREGAR ESTA LÍNEA
$router->get('/usuarios/usuariosAPI', [UsuarioController::class, 'usuariosAPI']);

// Para marcas desde AsignacionMarcasController - VERIFICAR QUE EXISTE
$router->get('/asignacionmarcas/marcasAPI', [AsignacionMarcasController::class, 'marcasAPI']);


// Rutas para AsignacionMarcas
$router->get('/final_armamento/asignacionmarcas', [AsignacionMarcasController::class, 'renderizarPAgina']);

// APIs para asignaciones
$router->post('/final_armamento/asignacionmarcas/guardarAPI', [AsignacionMarcasController::class, 'guardarAPI']);
$router->get('/final_armamento/asignacionmarcas/buscarAPI', [AsignacionMarcasController::class, 'buscarAPI']);
$router->post('/final_armamento/asignacionmarcas/modificarAPI', [AsignacionMarcasController::class, 'modificarAPI']);
$router->get('/final_armamento/asignacionmarcas/EliminarAPI', [AsignacionMarcasController::class, 'EliminarAPI']);

// APIs para obtener datos
$router->get('/final_armamento/asignacionmarcas/marcasAPI', [AsignacionMarcasController::class, 'marcasAPI']);
$router->get('/final_armamento/usuarios/usuariosAPI', [UsuarioController::class, 'usuariosAPI']); // Asegúrate de que esta ruta exista

// Ruta de testing
$router->get('/final_armamento/asignacionmarcas/testAPI', [AsignacionMarcasController::class, 'testAPI']);


//Historial de Actividades
$router->get('/historial', [HistorialController::class, 'renderizarPagina']);
$router->get('/historial/buscarAPI', [HistorialController::class, 'buscarAPI']);
$router->get('/historial/tiposActividadAPI', [HistorialController::class, 'tiposActividadAPI']);
$router->get('/historial/modulosAPI', [HistorialController::class, 'modulosAPI']);
$router->get('/historial/estadisticasAPI', [HistorialController::class, 'estadisticasAPI']);
$router->get('/historial/detalleAPI', [HistorialController::class, 'detalleAPI']);
$router->post('/historial/limpiarHistorialAPI', [HistorialController::class, 'limpiarHistorialAPI']);

$router->get('/sin-permisos', [AppController::class, 'sinPermisos']);




//roles 

$router->get('/roles', [RolController::class, 'renderizarPAgina']);
$router->post('/roles/guardarAPI', [RolController::class, 'guardarAPI']);
$router->get('/roles/buscarAPI', [RolController::class, 'buscarAPI']);
$router->post('/roles/modificarAPI', [RolController::class, 'modificarAPI']);
$router->get('/roles/eliminarAPI', [RolController::class, 'eliminarAPI']);
$router->get('/roles/eliminarRol', [RolController::class, 'EliminarRol']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();   
