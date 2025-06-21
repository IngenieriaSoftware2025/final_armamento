<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class EstadisticaController extends ActiveRecord{
    
    public static function renderizarPagina(Router $router){
      

        $router->render('estadisticas/index', []);
    }

    // 1. Gráfica de Modelos más utilizados/asignados
    public static function buscarModelosAPI(){
        try {
            $sql = "SELECT m.nombre_modelo as modelo, m.id_modelo, COUNT(am.id_asignacion) as cantidad_asignaciones
                    FROM lopez_modelos m
                    LEFT JOIN lopez_marcas ma ON m.id_marca = ma.id_marca
                    LEFT JOIN lopez_asignacion_marcas am ON ma.id_marca = am.id_marca
                    WHERE m.activo = 'T' AND (am.activo = 'T' OR am.activo IS NULL)
                    GROUP BY m.id_modelo, m.nombre_modelo
                    ORDER BY cantidad_asignaciones DESC";
            
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
                'mensaje' => 'Error al obtener los modelos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // 2. Gráfica de Marcas más asignadas
    public static function buscarMarcasAPI(){
        try {
            $sql = "SELECT ma.nombre_marca as marca, ma.id_marca, COUNT(am.id_asignacion) as cantidad_asignaciones
                    FROM lopez_marcas ma
                    LEFT JOIN lopez_asignacion_marcas am ON ma.id_marca = am.id_marca
                    WHERE ma.activo = 'T' AND (am.activo = 'T' OR am.activo IS NULL)
                    GROUP BY ma.id_marca, ma.nombre_marca
                    ORDER BY cantidad_asignaciones DESC";
            
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

    // 3. Gráfica de Usuarios del sistema
    public static function buscarUsuariosAPI(){
        try {
            $sql = "SELECT u.nombre_completo as usuario, u.id_usuario, 
                           r.nombre_rol as rol,
                           COUNT(am.id_asignacion) as armamentos_asignados
                    FROM lopez_usuarios u
                    INNER JOIN lopez_roles r ON u.id_rol = r.id_rol
                    LEFT JOIN lopez_asignacion_marcas am ON u.id_usuario = am.id_usuario AND am.activo = 'T'
                    WHERE u.activo = 'T'
                    GROUP BY u.id_usuario, u.nombre_completo, r.nombre_rol
                    ORDER BY armamentos_asignados DESC";
            
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

    // 4. Gráfica de usuarios con más armamentos asignados
    public static function buscarAsignacionesAPI(){
        try {
            $sql = "SELECT u.nombre_completo as usuario, u.id_usuario, 
                           COUNT(am.id_asignacion) as total_armamentos
                    FROM lopez_usuarios u
                    INNER JOIN lopez_asignacion_marcas am ON u.id_usuario = am.id_usuario
                    INNER JOIN lopez_marcas ma ON am.id_marca = ma.id_marca
                    WHERE u.activo = 'T' AND am.activo = 'T' AND ma.activo = 'T'
                    GROUP BY u.id_usuario, u.nombre_completo
                    ORDER BY total_armamentos DESC
                    FIRST 10";
            
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

    // Método adicional: Estadísticas por rol de usuario
    public static function buscarEstadisticasRolAPI(){
        try {
            $sql = "SELECT r.nombre_rol as rol, r.id_rol,
                           COUNT(DISTINCT u.id_usuario) as total_usuarios,
                           COUNT(am.id_asignacion) as total_asignaciones
                    FROM lopez_roles r
                    LEFT JOIN lopez_usuarios u ON r.id_rol = u.id_rol AND u.activo = 'T'
                    LEFT JOIN lopez_asignacion_marcas am ON u.id_usuario = am.id_usuario AND am.activo = 'T'
                    GROUP BY r.id_rol, r.nombre_rol
                    ORDER BY total_asignaciones DESC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas por rol obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener estadísticas por rol',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // Método adicional: Resumen general del sistema
    public static function buscarResumenGeneralAPI(){
        try {
            // Obtener totales generales
            $sqlTotales = "SELECT 
                           (SELECT COUNT(*) FROM lopez_usuarios WHERE activo = 'T') as total_usuarios,
                           (SELECT COUNT(*) FROM lopez_marcas WHERE activo = 'T') as total_marcas,
                           (SELECT COUNT(*) FROM lopez_modelos WHERE activo = 'T') as total_modelos,
                           (SELECT COUNT(*) FROM lopez_asignacion_marcas WHERE activo = 'T') as total_asignaciones";
            
            $totales = self::fetchArray($sqlTotales);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Resumen general obtenido correctamente',
                'data' => $totales[0] ?? []
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el resumen general',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}