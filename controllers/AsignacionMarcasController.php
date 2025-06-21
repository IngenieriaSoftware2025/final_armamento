<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\AsignacionMarcas;
use MVC\Router;


class AsignacionMarcasController extends ActiveRecord{
   public static function renderizarPAgina(Router $router)
{
    
    $router->render('asignacionmarcas/index', []);
}

    //Guardar Asignación
    public static function guardarAPI(){
        getHeadersApi();

        $usuario_validado = filter_var($_POST['id_usuario'], FILTER_VALIDATE_INT);
        if ($usuario_validado === false || $usuario_validado <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario válido'
            ]);
            return;
        }

        $marca_validada = filter_var($_POST['id_marca'], FILTER_VALIDATE_INT);
        if ($marca_validada === false || $marca_validada <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca válida'
            ]);
            return;
        }

        // Verificar que el usuario existe y está activo
        $sql_verificar_usuario = "SELECT id_usuario, nombre_completo FROM lopez_usuarios WHERE id_usuario = $usuario_validado AND activo = 'T'";
        $usuario_existe = self::fetchFirst($sql_verificar_usuario);
        
        if (!$usuario_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El usuario seleccionado no existe o está inactivo'
            ]);
            return;
        }

        // Verificar que la marca existe y está activa
        $sql_verificar_marca = "SELECT id_marca, nombre_marca FROM lopez_marcas WHERE id_marca = $marca_validada AND activo = 'T'";
        $marca_existe = self::fetchFirst($sql_verificar_marca);
        
        if (!$marca_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La marca seleccionada no existe o está inactiva'
            ]);
            return;
        }

        // Verificar que no exista ya esta asignación activa
        $sql_verificar_asignacion = "SELECT id_asignacion FROM lopez_asignacion_marcas 
                                    WHERE id_usuario = $usuario_validado 
                                    AND id_marca = $marca_validada 
                                    AND activo = 'T'";
        $asignacion_existe = self::fetchFirst($sql_verificar_asignacion);
        
        if ($asignacion_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Esta marca ya está asignada a este usuario'
            ]);
            return;
        }

        $_POST['observaciones'] = htmlspecialchars($_POST['observaciones'] ?? '');

        $usuario_asignador = filter_var($_POST['usuario_asignador'], FILTER_VALIDATE_INT);
        if ($usuario_asignador === false || $usuario_asignador <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar quién realiza la asignación'
            ]);
            return;
        }

        try {
            $data = new AsignacionMarcas([
                'id_usuario' => $usuario_validado,
                'id_marca' => $marca_validada,
                'usuario_asignador' => $usuario_asignador,
                'observaciones' => $_POST['observaciones'],
                'activo' => 'T'
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La asignación de marca ha sido registrada con éxito'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la asignación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar Asignaciones
    public static function buscarAPI(){
        try {
            $sql = "SELECT a.id_asignacion, a.id_usuario, a.id_marca, a.fecha_asignacion, 
                           a.observaciones, a.activo, a.usuario_asignador,
                           u.nombre_completo as usuario_asignado,
                           m.nombre_marca,
                           ua.nombre_completo as asignado_por
                    FROM lopez_asignacion_marcas a 
                    INNER JOIN lopez_usuarios u ON a.id_usuario = u.id_usuario 
                    INNER JOIN lopez_marcas m ON a.id_marca = m.id_marca
                    INNER JOIN lopez_usuarios ua ON a.usuario_asignador = ua.id_usuario
                    WHERE a.activo = 'T'
                    ORDER BY a.fecha_asignacion DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las asignaciones',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Modificar Asignación
    public static function modificarAPI(){
        getHeadersApi();

        // Verificar que se envíe el ID
        if (!isset($_POST['id_asignacion']) || empty($_POST['id_asignacion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de asignación requerido'
            ]);
            return;
        }

        $id = filter_var($_POST['id_asignacion'], FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de asignación inválido'
            ]);
            return;
        }

        $usuario_validado = filter_var($_POST['id_usuario'], FILTER_VALIDATE_INT);
        if ($usuario_validado === false || $usuario_validado <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario válido'
            ]);
            return;
        }

        $marca_validada = filter_var($_POST['id_marca'], FILTER_VALIDATE_INT);
        if ($marca_validada === false || $marca_validada <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca válida'
            ]);
            return;
        }

        // Verificar que el usuario existe y está activo
        $sql_verificar_usuario = "SELECT id_usuario FROM lopez_usuarios WHERE id_usuario = $usuario_validado AND activo = 'T'";
        $usuario_existe = self::fetchFirst($sql_verificar_usuario);
        
        if (!$usuario_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El usuario seleccionado no existe o está inactivo'
            ]);
            return;
        }

        // Verificar que la marca existe y está activa
        $sql_verificar_marca = "SELECT id_marca FROM lopez_marcas WHERE id_marca = $marca_validada AND activo = 'T'";
        $marca_existe = self::fetchFirst($sql_verificar_marca);
        
        if (!$marca_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La marca seleccionada no existe o está inactiva'
            ]);
            return;
        }

        // Verificar que no exista ya esta asignación activa (excluyendo el registro actual)
        $sql_verificar_asignacion = "SELECT id_asignacion FROM lopez_asignacion_marcas 
                                    WHERE id_usuario = $usuario_validado 
                                    AND id_marca = $marca_validada 
                                    AND activo = 'T'
                                    AND id_asignacion != $id";
        $asignacion_existe = self::fetchFirst($sql_verificar_asignacion);
        
        if ($asignacion_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Esta marca ya está asignada a este usuario'
            ]);
            return;
        }

        $_POST['observaciones'] = htmlspecialchars($_POST['observaciones'] ?? '');

        $usuario_asignador = filter_var($_POST['usuario_asignador'], FILTER_VALIDATE_INT);
        if ($usuario_asignador === false || $usuario_asignador <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar quién realiza la asignación'
            ]);
            return;
        }

        try {
            $data = AsignacionMarcas::find($id);
            
            if (!$data) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Asignación no encontrada'
                ]);
                return;
            }

            $datos_actualizar = [
                'id_usuario' => $usuario_validado,
                'id_marca' => $marca_validada,
                'usuario_asignador' => $usuario_asignador,
                'observaciones' => $_POST['observaciones'],
                'activo' => 'T'
            ];

            $data->sincronizar($datos_actualizar);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La asignación ha sido modificada con éxito'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la asignación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

// Agregar estos métodos a tu AsignacionMarcasController

public static function testEliminarAPI(){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'codigo' => 1,
        'mensaje' => 'Test exitoso - Ruta funcionando',
        'url' => $_SERVER['REQUEST_URI'],
        'metodo' => $_SERVER['REQUEST_METHOD'],
        'parametros' => $_GET
    ]);
    exit;
}

// Método mejorado que asegura JSON
public static function EliminarAPI(){
    // Limpiar output buffer
    if (ob_get_level()) {
        ob_clean();
    }
    
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    
    try {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de asignación requerido'
            ]);
            exit;
        }

        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID inválido: ' . $_GET['id']
            ]);
            exit;
        }

        // Verificar que existe la asignación
        $sql_verificar = "SELECT a.id_asignacion, u.nombre_completo, m.nombre_marca 
                         FROM lopez_asignacion_marcas a
                         INNER JOIN lopez_usuarios u ON a.id_usuario = u.id_usuario
                         INNER JOIN lopez_marcas m ON a.id_marca = m.id_marca
                         WHERE a.id_asignacion = $id AND a.activo = 'T'";
        
        $asignacion = self::fetchFirst($sql_verificar);
        
        if (!$asignacion) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La asignación no existe o ya está eliminada'
            ]);
            exit;
        }

        // Eliminar (desactivar)
        $sql_eliminar = "UPDATE lopez_asignacion_marcas SET activo = 'F' WHERE id_asignacion = $id";
        $resultado = self::SQL($sql_eliminar);

        if ($resultado) {
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignación eliminada correctamente',
                'detalle' => "Se eliminó la asignación de '{$asignacion['nombre_marca']}' a '{$asignacion['nombre_completo']}'"
            ]);
        } else {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'No se pudo eliminar la asignación'
            ]);
        }
    
    } catch (Exception $e) {
        echo json_encode([
            'codigo' => 0,
            'mensaje' => 'Error al eliminar',
            'detalle' => $e->getMessage()
        ]);
    }
    exit;
}


    // Obtener usuarios activos
    public static function usuariosAPI(){
        try {
            $sql = "SELECT id_usuario, nombre_completo FROM lopez_usuarios WHERE activo = 'T' ORDER BY nombre_completo";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Obtener marcas activas
    public static function marcasAPI(){
        try {
            $sql = "SELECT id_marca, nombre_marca FROM lopez_marcas WHERE activo = 'T' ORDER BY nombre_marca";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marcas obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las marcas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Método auxiliar para eliminar/desactivar asignación
    public static function EliminarAsignacion($id, $situacion)
    {
        $sql = "UPDATE lopez_asignacion_marcas SET activo = '$situacion' WHERE id_asignacion = $id";
        return self::SQL($sql);
    }

    // Método para reactivar asignación
    public static function ReactivarAsignacion($id)
    {
        return self::EliminarAsignacion($id, 'T');
    }

    // Método temporal para testing
    public static function testAPI(){
        http_response_code(200);
        echo json_encode([
            'codigo' => 1,
            'mensaje' => 'Conexión exitosa - Test funcionando',
            'data' => ['test' => 'OK']
        ]);
    }
}