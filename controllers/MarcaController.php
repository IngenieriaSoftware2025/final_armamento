<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Marcas;
use MVC\Router;

class MarcaController extends ActiveRecord{
   public static function renderizarPagina(Router $router)
{
    // verificarPermisos('marcas'); 
    
    $router->render('marcas/index', []);
}

    //Guardar Marcas
    public static function guardarAPI(){
        getHeadersApi();

        $_POST['nombre_marca'] = htmlspecialchars($_POST['nombre_marca']);
        $cantidad_nombre_marca = strlen($_POST['nombre_marca']);

        if ($cantidad_nombre_marca < 2){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca debe tener al menos 2 caracteres'
            ]);
            return;
        }

        $marca_repetida = trim(strtolower($_POST['nombre_marca']));
        $sql_verificar = "SELECT id_marca FROM lopez_marcas 
                         WHERE LOWER(TRIM(nombre_marca)) = " . self::$db->quote($marca_repetida) . "
                         AND activo = 'T'";
        $marca_existe = self::fetchFirst($sql_verificar);
        
        if ($marca_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe una marca con este nombre'
            ]);
            return;
        }

        if (!empty($_POST['descripcion'])) {
            $_POST['descripcion'] = htmlspecialchars($_POST['descripcion']);
            $cantidad_descripcion = strlen($_POST['descripcion']);

            if ($cantidad_descripcion > 200) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción no puede exceder los 200 caracteres'
                ]);
                return;
            }
        }

        // Obtener el usuario actual de la sesión (ajusta según tu sistema de autenticación)
        $usuario_creacion = $_SESSION['usuario_id'] ?? 1; // Por defecto 1 si no hay sesión

        try {
            $data = new Marcas([
                'nombre_marca' => $_POST['nombre_marca'],
                'descripcion' => $_POST['descripcion'],
                'usuario_creacion' => $usuario_creacion,
                'activo' => 'T'
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La marca ha sido registrada con éxito'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar Marcas
    public static function buscarAPI(){
        try {
            $sql = "SELECT m.id_marca, m.nombre_marca, m.descripcion, m.activo, 
                           m.fecha_creacion, u.nombre_completo as usuario_creacion
                    FROM lopez_marcas m 
                    LEFT JOIN lopez_usuarios u ON m.usuario_creacion = u.id_usuario 
                    WHERE m.activo = 'T'
                    ORDER BY m.nombre_marca";
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

    //Modificar Marcas
    public static function modificarAPI(){
        getHeadersApi();

        $id = $_POST['id_marca'];

        $_POST['nombre_marca'] = htmlspecialchars($_POST['nombre_marca']);
        $cantidad_nombre_marca = strlen($_POST['nombre_marca']);

        if ($cantidad_nombre_marca < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca debe tener al menos 2 caracteres'
            ]);
            return;
        }

        $marca_repetida = trim(strtolower($_POST['nombre_marca']));
        $sql_verificar = "SELECT id_marca FROM lopez_marcas 
                         WHERE LOWER(TRIM(nombre_marca)) = " . self::$db->quote($marca_repetida) . "
                         AND activo = 'T' 
                         AND id_marca != " . (int)$id;
        $marca_existe = self::fetchFirst($sql_verificar);
        
        if ($marca_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otra marca con este nombre'
            ]);
            return;
        }

        if (!empty($_POST['descripcion'])) {
            $_POST['descripcion'] = htmlspecialchars($_POST['descripcion']);
            $cantidad_descripcion = strlen($_POST['descripcion']);

            if ($cantidad_descripcion > 200) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La descripción no puede exceder los 200 caracteres'
                ]);
                return;
            }
        }

        try {
            $data = Marcas::find($id);
            
            $datos_actualizar = [
                'nombre_marca' => $_POST['nombre_marca'],
                'descripcion' => $_POST['descripcion'],
                'activo' => 'T'
            ];

            $data->sincronizar($datos_actualizar);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información de la marca ha sido modificada con éxito'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Eliminar Marca
    public static function EliminarAPI(){
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $sql_verificar = "SELECT id_marca, nombre_marca FROM lopez_marcas WHERE id_marca = $id AND activo = 'T'";
            $marca = self::fetchFirst($sql_verificar);
            
            if (!$marca) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La marca no existe o ya está inactiva'
                ]);
                return;
            }

            // Verificar si la marca está siendo utilizada en otras tablas (opcional)
            // Ejemplo: si tienes una tabla de productos que use esta marca
            /*
            $sql_verificar_uso = "SELECT COUNT(*) as total FROM lopez_productos WHERE id_marca = $id AND activo = 'T'";
            $uso_marca = self::fetchFirst($sql_verificar_uso);
            
            if ($uso_marca['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar la marca porque está siendo utilizada en productos'
                ]);
                return;
            }
            */

            self::EliminarMarca($id, 'F');

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La marca ha sido desactivada correctamente',
                'detalle' => "Marca '{$marca['nombre_marca']}' desactivada exitosamente"
            ]);
        
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar la marca',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function EliminarMarca($id, $situacion)
    {
        $sql = "UPDATE lopez_marcas SET activo = '$situacion' WHERE id_marca = $id";
        return self::SQL($sql);
    }

    public static function ReactivarMarca($id)
    {
        return self::EliminarMarca($id, 'T');
    }
}