<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Modelos;
use MVC\Router;

class ModeloController extends ActiveRecord{
   public static function renderizarPagina(Router $router)
{
  
    
    $router->render('modelos/index', []);
}

    //Guardar Modelos
    public static function guardarAPI(){
        getHeadersApi();

        // Validar marca
        $marca_validada = filter_var($_POST['id_marca'], FILTER_VALIDATE_INT);
        if ($marca_validada === false || $marca_validada <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca válida'
            ]);
            return;
        }

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

        $_POST['id_marca'] = $marca_validada;

        // Validar nombre del modelo
        $_POST['nombre_modelo'] = htmlspecialchars($_POST['nombre_modelo']);
        $cantidad_nombre_modelo = strlen($_POST['nombre_modelo']);

        if ($cantidad_nombre_modelo < 2){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del modelo debe tener al menos 2 caracteres'
            ]);
            return;
        }

        // Verificar duplicado (único por marca)
        $modelo_repetido = trim(strtolower($_POST['nombre_modelo']));
        $sql_verificar = "SELECT id_modelo FROM lopez_modelos 
                         WHERE LOWER(TRIM(nombre_modelo)) = " . self::$db->quote($modelo_repetido) . "
                         AND id_marca = " . $_POST['id_marca'] . "
                         AND activo = 'T'";
        $modelo_existe = self::fetchFirst($sql_verificar);
        
        if ($modelo_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un modelo con este nombre para la marca seleccionada'
            ]);
            return;
        }

        // Validar especificaciones
        if (!empty($_POST['especificaciones'])) {
            $_POST['especificaciones'] = htmlspecialchars($_POST['especificaciones']);
            $cantidad_especificaciones = strlen($_POST['especificaciones']);

            if ($cantidad_especificaciones > 100) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Las especificaciones no pueden exceder los 100 caracteres'
                ]);
                return;
            }
        }

        // Validar precio de referencia
        if (!empty($_POST['precio_referencia'])) {
            $precio = filter_var($_POST['precio_referencia'], FILTER_VALIDATE_FLOAT);
            if ($precio === false || $precio < 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El precio de referencia debe ser un número válido y mayor o igual a 0'
                ]);
                return;
            }

            if ($precio > 99999999.99) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El precio de referencia excede el límite máximo'
                ]);
                return;
            }

            $_POST['precio_referencia'] = $precio;
        } else {
            $_POST['precio_referencia'] = null;
        }

        try {
            $data = new Modelos([
                'id_marca' => $_POST['id_marca'],
                'nombre_modelo' => $_POST['nombre_modelo'],
                'especificaciones' => $_POST['especificaciones'],
                'precio_referencia' => $_POST['precio_referencia'],
                'activo' => 'T'
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El modelo ha sido registrado con éxito'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el modelo',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar Modelos
    public static function buscarAPI(){
        try {
            $sql = "SELECT m.id_modelo, m.id_marca, m.nombre_modelo, m.especificaciones, 
                           m.precio_referencia, m.activo, m.fecha_creacion,
                           ma.nombre_marca
                    FROM lopez_modelos m 
                    INNER JOIN lopez_marcas ma ON m.id_marca = ma.id_marca 
                    WHERE m.activo = 'T'
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
                'mensaje' => 'Error al obtener los modelos',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Modificar Modelos
    public static function modificarAPI(){
        getHeadersApi();

        $id = $_POST['id_modelo'];

        // Validar marca
        $marca_validada = filter_var($_POST['id_marca'], FILTER_VALIDATE_INT);
        if ($marca_validada === false || $marca_validada <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca válida'
            ]);
            return;
        }

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

        $_POST['id_marca'] = $marca_validada;

        // Validar nombre del modelo
        $_POST['nombre_modelo'] = htmlspecialchars($_POST['nombre_modelo']);
        $cantidad_nombre_modelo = strlen($_POST['nombre_modelo']);

        if ($cantidad_nombre_modelo < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del modelo debe tener al menos 2 caracteres'
            ]);
            return;
        }

        // Verificar duplicado (único por marca, excluyendo el actual)
        $modelo_repetido = trim(strtolower($_POST['nombre_modelo']));
        $sql_verificar = "SELECT id_modelo FROM lopez_modelos 
                         WHERE LOWER(TRIM(nombre_modelo)) = " . self::$db->quote($modelo_repetido) . "
                         AND id_marca = " . $_POST['id_marca'] . "
                         AND activo = 'T' 
                         AND id_modelo != " . (int)$id;
        $modelo_existe = self::fetchFirst($sql_verificar);
        
        if ($modelo_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro modelo con este nombre para la marca seleccionada'
            ]);
            return;
        }

        // Validar especificaciones
        if (!empty($_POST['especificaciones'])) {
            $_POST['especificaciones'] = htmlspecialchars($_POST['especificaciones']);
            $cantidad_especificaciones = strlen($_POST['especificaciones']);

            if ($cantidad_especificaciones > 100) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Las especificaciones no pueden exceder los 100 caracteres'
                ]);
                return;
            }
        }

        // Validar precio de referencia
        if (!empty($_POST['precio_referencia'])) {
            $precio = filter_var($_POST['precio_referencia'], FILTER_VALIDATE_FLOAT);
            if ($precio === false || $precio < 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El precio de referencia debe ser un número válido y mayor o igual a 0'
                ]);
                return;
            }

            if ($precio > 99999999.99) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El precio de referencia excede el límite máximo'
                ]);
                return;
            }

            $_POST['precio_referencia'] = $precio;
        } else {
            $_POST['precio_referencia'] = null;
        }

        try {
            $data = Modelos::find($id);
            
            $datos_actualizar = [
                'id_marca' => $_POST['id_marca'],
                'nombre_modelo' => $_POST['nombre_modelo'],
                'especificaciones' => $_POST['especificaciones'],
                'precio_referencia' => $_POST['precio_referencia'],
                'activo' => 'T'
            ];

            $data->sincronizar($datos_actualizar);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del modelo ha sido modificada con éxito'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el modelo',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Eliminar Modelo
    public static function EliminarAPI(){
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $sql_verificar = "SELECT m.id_modelo, m.nombre_modelo, ma.nombre_marca 
                             FROM lopez_modelos m 
                             INNER JOIN lopez_marcas ma ON m.id_marca = ma.id_marca
                             WHERE m.id_modelo = $id AND m.activo = 'T'";
            $modelo = self::fetchFirst($sql_verificar);
            
            if (!$modelo) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El modelo no existe o ya está inactivo'
                ]);
                return;
            }

           
            self::EliminarModelo($id, 'F');

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El modelo ha sido desactivado correctamente',
                'detalle' => "Modelo '{$modelo['nombre_modelo']}' de la marca '{$modelo['nombre_marca']}' desactivado exitosamente"
            ]);
        
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el modelo',
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

    public static function EliminarModelo($id, $situacion)
    {
        $sql = "UPDATE lopez_modelos SET activo = '$situacion' WHERE id_modelo = $id";
        return self::SQL($sql);
    }

    public static function ReactivarModelo($id)
    {
        return self::EliminarModelo($id, 'T');
    }
}