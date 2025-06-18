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

    //Buscar clientes con más productos comprados
    public static function buscarClientesAPI(){
        try {
            $sql = "SELECT usuario_nombres as cliente, usuario_id, COUNT(*) as total_productos
                    FROM usuarios u
                    INNER JOIN ventas v ON u.usuario_id = v.venta_cliente_id  
                    WHERE u.usuario_situacion = 1
                    GROUP BY usuario_id, usuario_nombres
                    ORDER BY total_productos DESC
                    LIMIT 10";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los clientes',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    
    //Buscar ventas por mes
      public static function buscarVentasMesAPI(){
        try {
            
            $sql = "SELECT venta_id, venta_total, venta_fecha FROM ventas ORDER BY venta_fecha";
            
            $ventasData = self::fetchArray($sql);
            
           
            $ventasPorMes = [];
            $meses = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            
           
            for($i = 1; $i <= 12; $i++) {
                $ventasPorMes[$i] = [
                    'mes' => $meses[$i],
                    'numero_mes' => $i,
                    'total_ventas' => 0,
                    'total_ingresos' => 0
                ];
            }
            
          
            foreach($ventasData as $venta) {
                $fecha = $venta['venta_fecha'];
                $mes = (int)date('n', strtotime($fecha)); // n = mes sin ceros iniciales (1-12)
                
                $ventasPorMes[$mes]['total_ventas']++;
                $ventasPorMes[$mes]['total_ingresos'] += floatval($venta['venta_total']);
            }
            
            
            $resultado = array_values($ventasPorMes);
            
         
            $resultado = array_filter($resultado, function($mes) {
                return $mes['total_ventas'] > 0;
            });
            
           
            $resultado = array_values($resultado);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Ventas por mes obtenidas correctamente',
                'data' => $resultado
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las ventas por mes',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}