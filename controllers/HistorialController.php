<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\HistorialActividades;
use MVC\Router;

class HistorialController extends ActiveRecord {
    
    public static function renderizarPagina(Router $router) {
        
        $router->render('historial/index', []);
    }

    // Buscar historial con filtros
    public static function buscarAPI() {
        try {
            $filtros = [];
            $where_conditions = ["h.id_actividad IS NOT NULL"];
            
            // Filtro por usuario
            if (!empty($_GET['usuario'])) {
                $usuario_id = filter_var($_GET['usuario'], FILTER_VALIDATE_INT);
                if ($usuario_id) {
                    $where_conditions[] = "h.id_usuario = $usuario_id";
                }
            }
            
            // Filtro por tipo de actividad
            if (!empty($_GET['tipo_actividad'])) {
                $tipo = self::$db->quote($_GET['tipo_actividad']);
                $where_conditions[] = "h.tipo_actividad = $tipo";
            }
            
            // Filtro por módulo
            if (!empty($_GET['modulo'])) {
                $modulo = self::$db->quote($_GET['modulo']);
                $where_conditions[] = "h.modulo = $modulo";
            }
            
            // Filtro por fecha desde
            if (!empty($_GET['fecha_desde'])) {
                $fecha_desde = self::$db->quote($_GET['fecha_desde'] . ' 00:00:00');
                $where_conditions[] = "h.fecha_actividad >= $fecha_desde";
            }
            
            // Filtro por fecha hasta
            if (!empty($_GET['fecha_hasta'])) {
                $fecha_hasta = self::$db->quote($_GET['fecha_hasta'] . ' 23:59:59');
                $where_conditions[] = "h.fecha_actividad <= $fecha_hasta";
            }

            $where_clause = implode(' AND ', $where_conditions);

            $sql = "SELECT h.id_actividad, h.id_usuario, h.tipo_actividad, h.modulo, 
                           h.descripcion, h.tabla_afectada, h.id_registro_afectado, 
                           h.ip_usuario, h.fecha_actividad, h.datos_anteriores, h.datos_nuevos,
                           u.nombre_completo, u.nombre_usuario
                    FROM lopez_historial_actividades h 
                    LEFT JOIN lopez_usuarios u ON h.id_usuario = u.id_usuario 
                    WHERE $where_clause
                    ORDER BY h.fecha_actividad DESC";
            
            $data = self::fetchArray($sql);

            // Formatear datos para mostrar mejor
            foreach ($data as &$registro) {
                $registro['fecha_actividad_formato'] = date('d/m/Y H:i:s', strtotime($registro['fecha_actividad']));
                $registro['nombre_usuario_display'] = $registro['nombre_completo'] ?: 'Usuario no encontrado';
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Historial obtenido correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el historial',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Obtener tipos de actividad únicos
    public static function tiposActividadAPI() {
        try {
            $sql = "SELECT DISTINCT tipo_actividad FROM lopez_historial_actividades ORDER BY tipo_actividad";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tipos de actividad obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener tipos de actividad',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Obtener módulos únicos
    public static function modulosAPI() {
        try {
            $sql = "SELECT DISTINCT modulo FROM lopez_historial_actividades WHERE modulo IS NOT NULL ORDER BY modulo";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Módulos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener módulos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Obtener estadísticas del historial
    public static function estadisticasAPI() {
        try {
            // Total de actividades
            $sql_total = "SELECT COUNT(*) as total FROM lopez_historial_actividades";
            $total = self::fetchFirst($sql_total);

            // Actividades por tipo (últimos 30 días)
            $sql_por_tipo = "SELECT tipo_actividad, COUNT(*) as cantidad 
                            FROM lopez_historial_actividades 
                            WHERE fecha_actividad >= (CURRENT - INTERVAL 30 DAY TO DAY)
                            GROUP BY tipo_actividad 
                            ORDER BY cantidad DESC";
            $por_tipo = self::fetchArray($sql_por_tipo);

            // Actividades por módulo (últimos 30 días)
            $sql_por_modulo = "SELECT modulo, COUNT(*) as cantidad 
                              FROM lopez_historial_actividades 
                              WHERE fecha_actividad >= (CURRENT - INTERVAL 30 DAY TO DAY)
                              AND modulo IS NOT NULL
                              GROUP BY modulo 
                              ORDER BY cantidad DESC";
            $por_modulo = self::fetchArray($sql_por_modulo);

            // Usuarios más activos (últimos 30 días)
            $sql_usuarios_activos = "SELECT u.nombre_completo, u.nombre_usuario, COUNT(*) as actividades
                                    FROM lopez_historial_actividades h
                                    LEFT JOIN lopez_usuarios u ON h.id_usuario = u.id_usuario
                                    WHERE h.fecha_actividad >= (CURRENT - INTERVAL 30 DAY TO DAY)
                                    GROUP BY u.id_usuario, u.nombre_completo, u.nombre_usuario
                                    ORDER BY actividades DESC
                                    LIMIT 10";
            $usuarios_activos = self::fetchArray($sql_usuarios_activos);

            // Actividades por día (últimos 7 días)
            $sql_por_dia = "SELECT DATE(fecha_actividad) as fecha, COUNT(*) as cantidad
                           FROM lopez_historial_actividades 
                           WHERE fecha_actividad >= (CURRENT - INTERVAL 7 DAY TO DAY)
                           GROUP BY DATE(fecha_actividad)
                           ORDER BY fecha DESC";
            $por_dia = self::fetchArray($sql_por_dia);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'data' => [
                    'total_actividades' => $total['total'],
                    'actividades_por_tipo' => $por_tipo,
                    'actividades_por_modulo' => $por_modulo,
                    'usuarios_activos' => $usuarios_activos,
                    'actividades_por_dia' => $por_dia
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener estadísticas',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Ver detalles de una actividad específica
    public static function detalleAPI() {
        try {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de actividad inválido'
                ]);
                return;
            }

            $sql = "SELECT h.*, u.nombre_completo, u.nombre_usuario, u.email
                    FROM lopez_historial_actividades h 
                    LEFT JOIN lopez_usuarios u ON h.id_usuario = u.id_usuario 
                    WHERE h.id_actividad = $id";
            
            $data = self::fetchFirst($sql);

            if (!$data) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Actividad no encontrada'
                ]);
                return;
            }

            // Decodificar JSON si existe
            if ($data['datos_anteriores']) {
                $data['datos_anteriores_decoded'] = json_decode($data['datos_anteriores'], true);
            }
            if ($data['datos_nuevos']) {
                $data['datos_nuevos_decoded'] = json_decode($data['datos_nuevos'], true);
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Detalle obtenido correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el detalle',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Limpiar historial antiguo (solo registros mayores a X días)
    public static function limpiarHistorialAPI() {
        getHeadersApi();
        
        try {
            $dias = filter_var($_POST['dias'], FILTER_VALIDATE_INT);
            if (!$dias || $dias < 30) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe especificar al menos 30 días'
                ]);
                return;
            }

            $sql = "DELETE FROM lopez_historial_actividades 
                    WHERE fecha_actividad < (CURRENT - INTERVAL $dias DAY TO DAY)";
            
            $resultado = self::SQL($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => "Historial anterior a $dias días eliminado correctamente"
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al limpiar el historial',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}