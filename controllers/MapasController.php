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

class MapasController extends ActiveRecord{
    
    public static function renderizarPagina(Router $router){
        $router->render('mapas/index', []);
    }


    //Buscar
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

}
