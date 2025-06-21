<?php
// Archivo: includes/helpers/historial_automatico.php

use Model\HistorialActividades;

/**
 * HELPER GLOBAL AUTOMÁTICO
 * Registra historial automáticamente sin tocar tus CRUD existentes
 */

class HistorialAutomatico {
    
    private static $iniciado = false;
    private static $datosOperacion = [];
    
    /**
     * Iniciar captura automática de historial
     * SOLO LLAMAR ESTA FUNCIÓN AL INICIO DE TUS MÉTODOS CRUD
     */
    public static function iniciar() {
        if (self::$iniciado) return;
        
        self::$iniciado = true;
        self::$datosOperacion = [
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'metodo' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'datos_entrada' => $_POST ?: $_GET ?: [],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'N/A',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Interceptar el output
        ob_start([self::class, 'procesarRespuesta']);
    }
    
    /**
     * Procesar la respuesta y registrar historial si es exitosa
     */
    public static function procesarRespuesta($output) {
        try {
            // Intentar decodificar la respuesta JSON
            $respuesta = json_decode($output, true);
            
            // Si es respuesta exitosa, registrar historial
            if ($respuesta && isset($respuesta['codigo']) && $respuesta['codigo'] == 1) {
                self::registrarHistorialAuto($respuesta);
            }
            
        } catch (Exception $e) {
            error_log("Error procesando historial automático: " . $e->getMessage());
        }
        
        return $output;
    }
    
    /**
     * Registrar historial automáticamente basado en la URL y respuesta
     */
    private static function registrarHistorialAuto($respuesta) {
        try {
            $url = self::$datosOperacion['url'];
            
            // Detectar acción desde la URL
            $accion = self::detectarAccion($url);
            if (!$accion) return;
            
            // Detectar módulo desde la URL
            $modulo = self::detectarModulo($url);
            
            // Generar descripción automática
            $descripcion = self::generarDescripcion($accion, $modulo, $respuesta);
            
            // Detectar tabla afectada
            $tabla = self::detectarTabla($modulo);
            
            // Registrar en historial
            HistorialActividades::registrarActividad(
                $_SESSION['id_usuario'] ?? 1,
                $accion,
                $modulo,
                $descripcion,
                $tabla,
                self::extraerIdRegistro($respuesta),
                null,
                self::$datosOperacion['datos_entrada']
            );
            
            error_log("✅ Historial registrado automáticamente: $accion en $modulo");
            
        } catch (Exception $e) {
            error_log("❌ Error registrando historial automático: " . $e->getMessage());
        }
    }
    
    /**
     * Detectar acción desde la URL
     */
    private static function detectarAccion($url) {
        $url = strtolower($url);
        
        if (strpos($url, 'crear') !== false || strpos($url, 'create') !== false) {
            return 'CREAR';
        }
        
        if (strpos($url, 'actualizar') !== false || strpos($url, 'editar') !== false || 
            strpos($url, 'update') !== false || strpos($url, 'edit') !== false) {
            return 'EDITAR';
        }
        
        if (strpos($url, 'eliminar') !== false || strpos($url, 'delete') !== false) {
            return 'ELIMINAR';
        }
        
        if (strpos($url, 'asignar') !== false || strpos($url, 'assign') !== false) {
            return 'ASIGNAR';
        }
        
        if (strpos($url, 'desasignar') !== false || strpos($url, 'unassign') !== false) {
            return 'DESASIGNAR';
        }
        
        return null; // No es una operación que queremos registrar
    }
    
    /**
     * Detectar módulo desde la URL
     */
    private static function detectarModulo($url) {
        $url = strtolower($url);
        
        if (strpos($url, 'usuarios') !== false || strpos($url, 'user') !== false) {
            return 'USUARIOS';
        }
        
        if (strpos($url, 'marcas') !== false || strpos($url, 'marca') !== false) {
            return 'MARCAS';
        }
        
        if (strpos($url, 'modelos') !== false || strpos($url, 'modelo') !== false) {
            return 'MODELOS';
        }
        
        if (strpos($url, 'asignaciones') !== false || strpos($url, 'asignacion') !== false) {
            return 'ASIGNACIONES';
        }
        
        if (strpos($url, 'armamento') !== false || strpos($url, 'arma') !== false) {
            return 'ARMAMENTO';
        }
        
        if (strpos($url, 'login') !== false || strpos($url, 'logout') !== false) {
            return 'SISTEMA';
        }
        
        return 'SISTEMA'; // Por defecto
    }
    
    /**
     * Detectar tabla de base de datos según el módulo
     */
    private static function detectarTabla($modulo) {
        $tablas = [
            'USUARIOS' => 'lopez_usuarios',
            'MARCAS' => 'lopez_marcas',
            'MODELOS' => 'lopez_modelos',
            'ASIGNACIONES' => 'lopez_asignaciones',
            'ARMAMENTO' => 'lopez_armamento'
        ];
        
        return $tablas[$modulo] ?? null;
    }
    
    /**
     * Generar descripción automática
     */
    private static function generarDescripcion($accion, $modulo, $respuesta) {
        $datos = self::$datosOperacion['datos_entrada'];
        
        // Intentar obtener nombre o identificador del registro
        $identificador = '';
        if (isset($datos['nombre'])) {
            $identificador = "'{$datos['nombre']}'";
        } elseif (isset($datos['nombre_usuario'])) {
            $identificador = "'{$datos['nombre_usuario']}'";
        } elseif (isset($datos['nombre_marca'])) {
            $identificador = "'{$datos['nombre_marca']}'";
        } elseif (isset($datos['nombre_modelo'])) {
            $identificador = "'{$datos['nombre_modelo']}'";
        } elseif (isset($datos['id'])) {
            $identificador = "ID:{$datos['id']}";
        } elseif (isset($respuesta['id'])) {
            $identificador = "ID:{$respuesta['id']}";
        }
        
        $moduloSingular = [
            'USUARIOS' => 'Usuario',
            'MARCAS' => 'Marca',
            'MODELOS' => 'Modelo',
            'ASIGNACIONES' => 'Asignación',
            'ARMAMENTO' => 'Armamento'
        ];
        
        $entidad = $moduloSingular[$modulo] ?? 'Registro';
        
        switch ($accion) {
            case 'CREAR':
                return "$entidad $identificador creado correctamente";
            case 'EDITAR':
                return "$entidad $identificador actualizado correctamente";
            case 'ELIMINAR':
                return "$entidad $identificador eliminado correctamente";
            case 'ASIGNAR':
                return "$entidad $identificador asignado correctamente";
            case 'DESASIGNAR':
                return "$entidad $identificador desasignado correctamente";
            default:
                return "Operación $accion realizada en $entidad $identificador";
        }
    }
    
    /**
     * Extraer ID del registro desde la respuesta
     */
    private static function extraerIdRegistro($respuesta) {
        if (isset($respuesta['id'])) {
            return $respuesta['id'];
        }
        
        if (isset($respuesta['data']['id'])) {
            return $respuesta['data']['id'];
        }
        
        // Intentar obtener desde los datos de entrada
        $datos = self::$datosOperacion['datos_entrada'];
        if (isset($datos['id'])) {
            return $datos['id'];
        }
        
        return null;
    }
}

/**
 * FUNCIÓN SIMPLE PARA USAR EN TUS CRUD
 * Solo agregar esta línea al INICIO de cada método CRUD
 */
function iniciarHistorialAuto() {
    HistorialAutomatico::iniciar();
}

// ============================================
// CONFIGURACIÓN ADICIONAL OPCIONAL
// ============================================

/**
 * Configurar sesión automáticamente si no existe
 */
function configurarSesionHistorial() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Si no hay usuario en sesión, usar ID por defecto
    if (!isset($_SESSION['id_usuario'])) {
        $_SESSION['id_usuario'] = 1; // Usuario por defecto
        error_log("⚠️ Usando usuario por defecto para historial");
    }
}

/**
 * Auto-inicializar en cada request si se desea
 */
function autoIniciarHistorial() {
    // Solo para métodos que modifican datos
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
        configurarSesionHistorial();
        HistorialAutomatico::iniciar();
    }
}

// Descomentar la siguiente línea para activar el historial 100% automático
// autoIniciarHistorial();