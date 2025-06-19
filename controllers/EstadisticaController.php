<?php

namespace Controllers;

use Controllers\ProductoController;
use Exception;
use Model\ActiveRecord;
use Model\Ventas;
use Model\VentaDetalles;
use Model\Productos;
use Model\Usuarios;
use MVC\Router;

class EstadisticaController extends ActiveRecord{
    
    public static function renderizarPagina(Router $router){
        $router->render('estadisticas/index', []);
    }

    //Buscar productos vendidos (tu código original)
    public static function buscarAPI(){
        try {
            $sql = "SELECT pro_nombre as producto, pro_id, sum(detalle_cantidad) as cantidad from venta_detalles inner join productos on pro_id=
            detalle_producto_id group by pro_id, producto order by cantidad ASC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Productos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los productos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    // AGREGAR ESTOS MÉTODOS A TU EstadisticaController.php

    //Buscar tipos de armamentos más asignados
    public static function buscarTiposArmamentosAPI(){
        try {
            $sql = "SELECT 
                        ta.nombre_armamento as tipo_armamento,
                        ta.marca,
                        ta.modelo,
                        ta.calibre,
                        ta.id_tipo_armamento,
                        COUNT(aa.id_asignacion) as total_asignaciones,
                        COUNT(CASE WHEN aa.activo = 'T' THEN 1 END) as asignaciones_activas,
                        COUNT(CASE WHEN aa.activo = 'F' THEN 1 END) as armamentos_retirados
                    FROM lopez_tipos_armamentos ta
                    LEFT JOIN lopez_asignaciones_armamentos aa ON ta.id_tipo_armamento = aa.id_tipo_armamento
                    WHERE ta.activo = 'T'
                    GROUP BY ta.id_tipo_armamento, ta.nombre_armamento, ta.marca, ta.modelo, ta.calibre
                    ORDER BY total_asignaciones DESC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tipos de armamentos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los tipos de armamentos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar usuarios con más armamentos asignados
    public static function buscarUsuariosArmamentosAPI(){
        try {
            $sql = "SELECT 
                        u.nombre_completo as usuario,
                        u.nombre_usuario,
                        u.email,
                        u.id_usuario,
                        COUNT(aa.id_asignacion) as total_armamentos_asignados,
                        COUNT(CASE WHEN aa.activo = 'T' THEN 1 END) as armamentos_activos,
                        COUNT(CASE WHEN aa.activo = 'F' THEN 1 END) as armamentos_retirados,
                        MAX(aa.fecha_asignacion) as ultima_asignacion
                    FROM lopez_usuarios u
                    LEFT JOIN lopez_asignaciones_armamentos aa ON u.id_usuario = aa.id_usuario
                    WHERE u.activo = 'T'
                    GROUP BY u.id_usuario, u.nombre_completo, u.nombre_usuario, u.email
                    HAVING total_armamentos_asignados > 0
                    ORDER BY armamentos_activos DESC, total_armamentos_asignados DESC
                    LIMIT 15";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios con armamentos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios con armamentos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar marcas de armamentos más populares
    public static function buscarMarcasArmamentosAPI(){
        try {
            $sql = "SELECT 
                        ta.marca,
                        COUNT(DISTINCT ta.id_tipo_armamento) as tipos_disponibles,
                        COUNT(aa.id_asignacion) as total_asignaciones,
                        COUNT(CASE WHEN aa.activo = 'T' THEN 1 END) as asignaciones_activas,
                        COUNT(DISTINCT aa.id_usuario) as usuarios_diferentes,
                        GROUP_CONCAT(DISTINCT ta.nombre_armamento ORDER BY ta.nombre_armamento SEPARATOR ', ') as tipos_armamentos
                    FROM lopez_tipos_armamentos ta
                    LEFT JOIN lopez_asignaciones_armamentos aa ON ta.id_tipo_armamento = aa.id_tipo_armamento
                    WHERE ta.activo = 'T' AND ta.marca IS NOT NULL AND ta.marca != ''
                    GROUP BY ta.marca
                    ORDER BY total_asignaciones DESC, tipos_disponibles DESC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marcas de armamentos obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las marcas de armamentos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar modelos de armamentos y su uso
    public static function buscarModelosArmamentosAPI(){
        try {
            $sql = "SELECT 
                        ta.modelo,
                        ta.marca,
                        ta.calibre,
                        COUNT(DISTINCT ta.id_tipo_armamento) as variantes_disponibles,
                        COUNT(aa.id_asignacion) as total_asignaciones,
                        COUNT(CASE WHEN aa.activo = 'T' THEN 1 END) as asignaciones_activas,
                        COUNT(DISTINCT aa.id_usuario) as usuarios_asignados,
                        GROUP_CONCAT(DISTINCT ta.nombre_armamento ORDER BY ta.nombre_armamento SEPARATOR ' | ') as nombres_completos,
                        AVG(DATEDIFF(CURRENT_DATE, aa.fecha_asignacion)) as promedio_dias_asignado
                    FROM lopez_tipos_armamentos ta
                    LEFT JOIN lopez_asignaciones_armamentos aa ON ta.id_tipo_armamento = aa.id_tipo_armamento
                    WHERE ta.activo = 'T' AND ta.modelo IS NOT NULL AND ta.modelo != ''
                    GROUP BY ta.modelo, ta.marca, ta.calibre
                    ORDER BY total_asignaciones DESC, asignaciones_activas DESC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Modelos de armamentos obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los modelos de armamentos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar asignaciones por mes (similar a tu buscarVentasMesAPI)
    public static function buscarAsignacionesMesAPI(){
        try {
            $sql = "SELECT 
                        id_asignacion, 
                        fecha_asignacion, 
                        activo,
                        id_usuario,
                        id_tipo_armamento
                    FROM lopez_asignaciones_armamentos 
                    ORDER BY fecha_asignacion";
            
            $asignacionesData = self::fetchArray($sql);
            
            // Array de meses
            $asignacionesPorMes = [];
            $meses = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            
            // Inicializar todos los meses con 0
            for($i = 1; $i <= 12; $i++) {
                $asignacionesPorMes[$i] = [
                    'mes' => $meses[$i],
                    'numero_mes' => $i,
                    'total_asignaciones' => 0,
                    'asignaciones_activas' => 0,
                    'asignaciones_retiradas' => 0,
                    'usuarios_diferentes' => 0,
                    'tipos_diferentes' => 0
                ];
            }
            
            // Procesar los datos
            $usuariosPorMes = [];
            $tiposPorMes = [];
            
            foreach($asignacionesData as $asignacion) {
                $fecha = $asignacion['fecha_asignacion'];
                $mes = (int)date('n', strtotime($fecha)); // n = mes sin ceros iniciales (1-12)
                
                $asignacionesPorMes[$mes]['total_asignaciones']++;
                
                if($asignacion['activo'] == 'T') {
                    $asignacionesPorMes[$mes]['asignaciones_activas']++;
                } else {
                    $asignacionesPorMes[$mes]['asignaciones_retiradas']++;
                }
                
                // Contar usuarios únicos por mes
                if(!isset($usuariosPorMes[$mes])) {
                    $usuariosPorMes[$mes] = [];
                }
                $usuariosPorMes[$mes][$asignacion['id_usuario']] = true;
                
                // Contar tipos únicos por mes
                if(!isset($tiposPorMes[$mes])) {
                    $tiposPorMes[$mes] = [];
                }
                $tiposPorMes[$mes][$asignacion['id_tipo_armamento']] = true;
            }
            
            // Calcular usuarios y tipos únicos
            for($i = 1; $i <= 12; $i++) {
                $asignacionesPorMes[$i]['usuarios_diferentes'] = isset($usuariosPorMes[$i]) ? count($usuariosPorMes[$i]) : 0;
                $asignacionesPorMes[$i]['tipos_diferentes'] = isset($tiposPorMes[$i]) ? count($tiposPorMes[$i]) : 0;
            }
            
            // Convertir a array indexado y filtrar meses sin asignaciones
            $resultado = array_values($asignacionesPorMes);
            
            // Filtrar solo meses con asignaciones
            $resultado = array_filter($resultado, function($mes) {
                return $mes['total_asignaciones'] > 0;
            });
            
            // Reindexar el array
            $resultado = array_values($resultado);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asignaciones por mes obtenidas correctamente',
                'data' => $resultado
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las asignaciones por mes',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar estadísticas generales de armamentos
    public static function buscarEstadisticasGeneralesAPI(){
        try {
            $sql = "SELECT 
                        -- Estadísticas de tipos de armamentos
                        (SELECT COUNT(*) FROM lopez_tipos_armamentos WHERE activo = 'T') as total_tipos_armamentos,
                        
                        -- Estadísticas de usuarios
                        (SELECT COUNT(*) FROM lopez_usuarios WHERE activo = 'T') as total_usuarios_activos,
                        (SELECT COUNT(DISTINCT id_usuario) FROM lopez_asignaciones_armamentos WHERE activo = 'T') as usuarios_con_armamentos,
                        
                        -- Estadísticas de asignaciones
                        (SELECT COUNT(*) FROM lopez_asignaciones_armamentos WHERE activo = 'T') as asignaciones_activas,
                        (SELECT COUNT(*) FROM lopez_asignaciones_armamentos WHERE activo = 'F') as armamentos_retirados,
                        (SELECT COUNT(*) FROM lopez_asignaciones_armamentos) as total_asignaciones_historicas,
                        
                        -- Estadísticas de marcas y modelos
                        (SELECT COUNT(DISTINCT marca) FROM lopez_tipos_armamentos WHERE activo = 'T' AND marca IS NOT NULL) as marcas_diferentes,
                        (SELECT COUNT(DISTINCT modelo) FROM lopez_tipos_armamentos WHERE activo = 'T' AND modelo IS NOT NULL) as modelos_diferentes,
                        (SELECT COUNT(DISTINCT calibre) FROM lopez_tipos_armamentos WHERE activo = 'T' AND calibre IS NOT NULL) as calibres_diferentes,
                        
                        -- Estadísticas de tiempo
                        (SELECT DATE(MIN(fecha_asignacion)) FROM lopez_asignaciones_armamentos) as primera_asignacion,
                        (SELECT DATE(MAX(fecha_asignacion)) FROM lopez_asignaciones_armamentos) as ultima_asignacion";
            
            $data = self::fetchArray($sql);
            
            // Calcular porcentajes
            if(count($data) > 0) {
                $stats = $data[0];
                $stats['porcentaje_usuarios_con_armamentos'] = $stats['total_usuarios_activos'] > 0 ? 
                    round(($stats['usuarios_con_armamentos'] / $stats['total_usuarios_activos']) * 100, 2) : 0;
                    
                $stats['porcentaje_armamentos_activos'] = $stats['total_asignaciones_historicas'] > 0 ? 
                    round(($stats['asignaciones_activas'] / $stats['total_asignaciones_historicas']) * 100, 2) : 0;
                    
                $data[0] = $stats;
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Estadísticas generales obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las estadísticas generales',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar top usuarios que asignan armamentos (quién asigna más)
    public static function buscarTopAsignadorasAPI(){
        try {
            $sql = "SELECT 
                        u.nombre_completo as asignador,
                        u.nombre_usuario,
                        u.id_usuario,
                        COUNT(aa.id_asignacion) as total_asignaciones_realizadas,
                        COUNT(CASE WHEN aa.activo = 'T' THEN 1 END) as asignaciones_activas_realizadas,
                        COUNT(DISTINCT aa.id_usuario) as usuarios_diferentes_asignados,
                        COUNT(DISTINCT aa.id_tipo_armamento) as tipos_diferentes_asignados,
                        MIN(aa.fecha_asignacion) as primera_asignacion_realizada,
                        MAX(aa.fecha_asignacion) as ultima_asignacion_realizada
                    FROM lopez_usuarios u
                    INNER JOIN lopez_asignaciones_armamentos aa ON u.id_usuario = aa.usuario_asigno
                    WHERE u.activo = 'T'
                    GROUP BY u.id_usuario, u.nombre_completo, u.nombre_usuario
                    ORDER BY total_asignaciones_realizadas DESC, asignaciones_activas_realizadas DESC
                    LIMIT 10";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Top asignadores obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los top asignadores',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}