<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Usuarios;
use MVC\Router;

class UsuarioController extends ActiveRecord{
   public static function renderizarPAgina(Router $router)
{
    
    $router->render('usuarios/index', []);
}

    // Función auxiliar para manejar la subida de fotos
    private static function manejarSubidaFoto($archivo_foto, $nombre_usuario = null) {
        if (!isset($archivo_foto) || $archivo_foto['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // No se subió archivo
        }

        if ($archivo_foto['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo');
        }

        // Validar tipo de archivo
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($archivo_foto['type'], $tipos_permitidos)) {
            throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG y GIF');
        }

        // Validar tamaño (2MB máximo)
        if ($archivo_foto['size'] > 2097152) {
            throw new Exception('El archivo es demasiado grande. Máximo 2MB');
        }

        // Crear directorio si no existe
        $directorio = $_SERVER['DOCUMENT_ROOT'] . '/uploads/usuarios/';
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Generar nombre único para el archivo
        $extension = pathinfo($archivo_foto['name'], PATHINFO_EXTENSION);
        $nombre_archivo = ($nombre_usuario ? $nombre_usuario . '_' : '') . uniqid() . '.' . $extension;
        $ruta_completa = $directorio . $nombre_archivo;

        // Mover archivo
        if (!move_uploaded_file($archivo_foto['tmp_name'], $ruta_completa)) {
            throw new Exception('Error al guardar el archivo');
        }

        return 'uploads/usuarios/' . $nombre_archivo;
    }

    // Función para eliminar foto anterior
    private static function eliminarFotoAnterior($ruta_foto) {
        if ($ruta_foto && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $ruta_foto)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $ruta_foto);
        }
    }

    //Guardar Usuarios
    public static function guardarAPI(){
        getHeadersApi();

        $_POST['nombre_usuario'] = htmlspecialchars($_POST['nombre_usuario']);
        $cantidad_nombre_usuario = strlen($_POST['nombre_usuario']);

        if ($cantidad_nombre_usuario < 3){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de usuario debe tener al menos 3 caracteres'
            ]);
            return;
        }

        $usuario_repetido = trim(strtolower($_POST['nombre_usuario']));
        $sql_verificar = "SELECT id_usuario FROM lopez_usuarios 
                         WHERE LOWER(TRIM(nombre_usuario)) = " . self::$db->quote($usuario_repetido) . "
                         AND activo = 'T'";
        $usuario_existe = self::fetchFirst($sql_verificar);
        
        if ($usuario_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un usuario con este nombre'
            ]);
            return;
        }

        $_POST['nombre_completo'] = htmlspecialchars($_POST['nombre_completo']);
        $cantidad_nombre_completo = strlen($_POST['nombre_completo']);

        if ($cantidad_nombre_completo < 3){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre completo debe tener al menos 3 caracteres'
            ]);
            return;
        }

        $_POST['password'] = htmlspecialchars($_POST['password']);
        $cantidad_password = strlen($_POST['password']);

        if ($cantidad_password < 6){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe tener al menos 6 caracteres'
            ]);
            return;
        }

        if (!empty($_POST['email'])) {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El formato del email no es válido'
                ]);
                return;
            }

            $email_repetido = trim(strtolower($_POST['email']));
            $sql_verificar_email = "SELECT id_usuario FROM lopez_usuarios 
                                   WHERE LOWER(TRIM(email)) = " . self::$db->quote($email_repetido) . "
                                   AND activo = 'T'";
            $email_existe = self::fetchFirst($sql_verificar_email);
            
            if ($email_existe) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un usuario con este email'
                ]);
                return;
            }
        }

        $rol_validado = filter_var($_POST['id_rol'], FILTER_VALIDATE_INT);
        if ($rol_validado === false || $rol_validado <= 0){
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un rol válido'
            ]);
            return;
        }

        $sql_verificar_rol = "SELECT id_rol FROM lopez_roles WHERE id_rol = $rol_validado";
        $rol_existe = self::fetchFirst($sql_verificar_rol);
        
        if (!$rol_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El rol seleccionado no existe'
            ]);
            return;
        }

        $_POST['id_rol'] = $rol_validado;

        try {
            // Manejar subida de foto
            $ruta_foto = null;
            if (isset($_FILES['foto'])) {
                $ruta_foto = self::manejarSubidaFoto($_FILES['foto'], $_POST['nombre_usuario']);
            }

            // HASHEAR LA CONTRASEÑA antes de guardar
            $passwordHasheado = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            $data = new Usuarios([
                'nombre_usuario' => $_POST['nombre_usuario'],
                'password' => $passwordHasheado,
                'nombre_completo' => $_POST['nombre_completo'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'],
                'foto' => $ruta_foto,
                'id_rol' => $_POST['id_rol'],
                'activo' => 'T'
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El usuario ha sido registrado con éxito'
            ]);

        } catch (Exception $e) {
            // Si hay error y se subió una foto, eliminarla
            if (isset($ruta_foto) && $ruta_foto) {
                self::eliminarFotoAnterior($ruta_foto);
            }
            
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar el usuario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Buscar Usuarios
    public static function buscarAPI(){
        try {
            $sql = "SELECT u.id_usuario, u.nombre_usuario, u.nombre_completo, u.email, 
                           u.telefono, u.foto, u.activo, r.nombre_rol, 
                           u.fecha_creacion, u.ultimo_acceso
                    FROM lopez_usuarios u 
                    INNER JOIN lopez_roles r ON u.id_rol = r.id_rol 
                    WHERE u.activo = 'T'
                    ORDER BY u.nombre_completo";
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

    //Modificar Usuarios
    public static function modificarAPI(){
        getHeadersApi();

        $id = $_POST['id_usuario'];

        $_POST['nombre_usuario'] = htmlspecialchars($_POST['nombre_usuario']);
        $cantidad_nombre_usuario = strlen($_POST['nombre_usuario']);

        if ($cantidad_nombre_usuario < 3) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de usuario debe tener al menos 3 caracteres'
            ]);
            return;
        }

        $usuario_repetido = trim(strtolower($_POST['nombre_usuario']));
        $sql_verificar = "SELECT id_usuario FROM lopez_usuarios 
                         WHERE LOWER(TRIM(nombre_usuario)) = " . self::$db->quote($usuario_repetido) . "
                         AND activo = 'T' 
                         AND id_usuario != " . (int)$id;
        $usuario_existe = self::fetchFirst($sql_verificar);
        
        if ($usuario_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro usuario con este nombre'
            ]);
            return;
        }

        $_POST['nombre_completo'] = htmlspecialchars($_POST['nombre_completo']);
        $cantidad_nombre_completo = strlen($_POST['nombre_completo']);

        if ($cantidad_nombre_completo < 3) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre completo debe tener al menos 3 caracteres'
            ]);
            return;
        }

        if (!empty($_POST['email'])) {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El formato del email no es válido'
                ]);
                return;
            }

            $email_repetido = trim(strtolower($_POST['email']));
            $sql_verificar_email = "SELECT id_usuario FROM lopez_usuarios 
                                   WHERE LOWER(TRIM(email)) = " . self::$db->quote($email_repetido) . "
                                   AND activo = 'T' 
                                   AND id_usuario != " . (int)$id;
            $email_existe = self::fetchFirst($sql_verificar_email);
            
            if ($email_existe) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otro usuario con este email'
                ]);
                return;
            }
        }

        $rol_validado = filter_var($_POST['id_rol'], FILTER_VALIDATE_INT);
        if ($rol_validado === false || $rol_validado <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un rol válido'
            ]);
            return;
        }

        $sql_verificar_rol = "SELECT id_rol FROM lopez_roles WHERE id_rol = $rol_validado";
        $rol_existe = self::fetchFirst($sql_verificar_rol);
        
        if (!$rol_existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El rol seleccionado no existe'
            ]);
            return;
        }

        $_POST['id_rol'] = $rol_validado;

        try {
            $data = Usuarios::find($id);
            $foto_anterior = $data->foto;
            
            $datos_actualizar = [
                'nombre_usuario' => $_POST['nombre_usuario'],
                'nombre_completo' => $_POST['nombre_completo'],
                'email' => $_POST['email'],
                'telefono' => $_POST['telefono'],
                'id_rol' => $_POST['id_rol'],
                'activo' => 'T'
            ];

            // Manejar nueva foto si se subió
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                $nueva_foto = self::manejarSubidaFoto($_FILES['foto'], $_POST['nombre_usuario']);
                $datos_actualizar['foto'] = $nueva_foto;
                
                // Eliminar foto anterior si existe
                if ($foto_anterior) {
                    self::eliminarFotoAnterior($foto_anterior);
                }
            }

            // Solo actualizar password si se envió uno nuevo
            if (!empty($_POST['password'])) {
                $_POST['password'] = htmlspecialchars($_POST['password']);
                $cantidad_password = strlen($_POST['password']);

                if ($cantidad_password < 6) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña debe tener al menos 6 caracteres'
                    ]);
                    return;
                }

                $datos_actualizar['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            $data->sincronizar($datos_actualizar);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del usuario ha sido modificada con éxito'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar el usuario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    //Eliminar Usuario
    public static function EliminarAPI(){
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $sql_verificar = "SELECT id_usuario, nombre_usuario, nombre_completo FROM lopez_usuarios WHERE id_usuario = $id AND activo = 'T'";
            $usuario = self::fetchFirst($sql_verificar);
            
            if (!$usuario) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario no existe o ya está inactivo'
                ]);
                return;
            }

            if (self::esUltimoAdministrador($id)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se puede eliminar el último administrador del sistema'
                ]);
                return;
            }

            self::EliminarUsuario($id, 'F');

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El usuario ha sido desactivado correctamente',
                'detalle' => "Usuario '{$usuario['nombre_completo']}' desactivado exitosamente"
            ]);
        
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar el usuario',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function rolesAPI(){
        try {
            $sql = "SELECT id_rol, nombre_rol FROM lopez_roles ORDER BY nombre_rol";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Roles obtenidos correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los roles',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function EliminarUsuario($id, $situacion)
    {
        $sql = "UPDATE lopez_usuarios SET activo = '$situacion' WHERE id_usuario = $id";
        return self::SQL($sql);
    }

    public static function esUltimoAdministrador($id_usuario)
    {
        $sql = "SELECT COUNT(*) as total FROM lopez_usuarios u 
                INNER JOIN lopez_roles r ON u.id_rol = r.id_rol 
                WHERE r.nombre_rol = 'Administrador' AND u.activo = 'T' AND u.id_usuario != $id_usuario";
        $resultado = self::fetchFirst($sql);
        return $resultado['total'] == 0;
    }

    public static function ReactivarUsuario($id)
    {
        return self::EliminarUsuario($id, 'T');
    }

    public static function usuariosAPI(){
        try {
            $sql = "SELECT id_usuario, nombre_usuario, nombre_completo FROM lopez_usuarios WHERE activo = 'T' ORDER BY nombre_completo";
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
}