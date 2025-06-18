<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\AsignacionesArmamento;
use MVC\Router;

class AsignacionesController extends ActiveRecord{
    
    public static function renderizarPagina(Router $router)
    {
        $router->render('asignaciones/index', []);
    }

    // TEST API - Para verificar que funciona
    public static function testAPI(){
        try {
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'API de asignaciones funcionando correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en test API',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Obtener usuarios activos - SIMPLIFICADO
    public static function usuariosAPI(){
        try {
            $sql = "SELECT id_usuario, nombre_completo, nombre_usuario 
                   FROM lopez_usuarios 
                   WHERE activo = 'T' 
                   ORDER BY nombre_completo";
            
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
                'mensaje' => 'Error al obtener usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Obtener modelos - SIMPLIFICADO
    public static function modelosAPI(){
        try {
            $sql = "SELECT m.id_modelo, m.nombre_modelo, ma.nombre_marca,
                           CONCAT(ma.nombre_marca, ' - ', m.nombre_modelo) as modelo_completo
                    FROM lopez_modelos m
                    INNER JOIN lopez_marcas ma ON m.id_marca = ma.id_marca
                    WHERE m.activo = 'T' AND ma.activo = 'T'
                    ORDER BY ma.nombre_marca, m.nombre_modelo";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Modelos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener modelos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Buscar Asignaciones - SIMPLIFICADO
    public static function buscarAPI(){
        try {
            $sql = "SELECT 
                        a.id_asignacion,
                        a.numero_serie,
                        a.fecha_asignacion,
                        a.fecha_devolucion,
                        a.estado,
                        a.observaciones,
                        u.nombre_completo as usuario,
                        m.nombre_modelo,
                        ma.nombre_marca,
                        CONCAT(ma.nombre_marca, ' - ', m.nombre_modelo) as armamento_completo
                    FROM lopez_asignaciones_armamento a
                    INNER JOIN lopez_usuarios u ON a.id_usuario = u.id_usuario
                    INNER JOIN lopez_modelos m ON a.id_modelo = m.id_modelo
                    INNER JOIN lopez_marcas ma ON m.id_marca = ma.id_marca
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
                'mensaje' => 'Error al obtener asignaciones',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Guardar Asignación - SIMPLIFICADO
    public static function guardarAPI(){
        try {
            // Obtener headers API si existe la función
            if (function_exists('getHeadersApi')) {
                getHeadersApi();
            }

            // Validaciones básicas
            if (empty($_POST['id_usuario']) || empty($_POST['id_modelo']) || empty($_POST['numero_serie'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Faltan campos obligatorios'
                ]);
                return;
            }

            // Sanitizar datos
            $id_usuario = (int) $_POST['id_usuario'];
            $id_modelo = (int) $_POST['id_modelo'];
            $numero_serie = htmlspecialchars(trim($_POST['numero_serie']));
            $fecha_asignacion = $_POST['fecha_asignacion'] ?? date('Y-m-d');
            $observaciones = htmlspecialchars($_POST['observaciones'] ?? '');

            // Crear nueva asignación
            $data = new AsignacionesArmamento([
                'id_usuario' => $id_usuario,
                'id_modelo' => $id_modelo,
                'numero_serie' => $numero_serie,
                'fecha_asignacion' => $fecha_asignacion,
                'estado' => 'ASIGNADO',
                'observaciones' => $observaciones,
                'activo' => 'T',
                'usuario_creacion' => 1
            ]);

            $resultado = $data->crear();

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignación creada exitosamente'
                ]);
            } else {
                throw new Exception('Error al insertar en base de datos');
            }

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar asignación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Modificar Asignación - SIMPLIFICADO
    public static function modificarAPI(){
        try {
            if (function_exists('getHeadersApi')) {
                getHeadersApi();
            }

            $id = (int) $_POST['id_asignacion'];
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de asignación inválido'
                ]);
                return;
            }

            $data = AsignacionesArmamento::find($id);
            if (!$data) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Asignación no encontrada'
                ]);
                return;
            }

            $datos_actualizar = [
                'numero_serie' => htmlspecialchars(trim($_POST['numero_serie'])),
                'estado' => $_POST['estado'],
                'observaciones' => htmlspecialchars($_POST['observaciones'] ?? '')
            ];

            if ($_POST['estado'] === 'DEVUELTO' && !empty($_POST['fecha_devolucion'])) {
                $datos_actualizar['fecha_devolucion'] = $_POST['fecha_devolucion'];
            }

            $data->sincronizar($datos_actualizar);
            $resultado = $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignación modificada exitosamente'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar asignación',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Eliminar Asignación
    public static function eliminarAPI(){
        try {
            $id = (int) $_GET['id'];
            
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID inválido'
                ]);
                return;
            }

            $sql = "UPDATE lopez_asignaciones_armamento SET activo = 'F' WHERE id_asignacion = $id";
            $resultado = self::SQL($sql);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Asignación eliminada correctamente'
                ]);
            } else {
                throw new Exception('Error al eliminar asignación');
            }

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar asignación',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}