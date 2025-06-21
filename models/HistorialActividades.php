<?php

namespace Model;

class HistorialActividades extends ActiveRecord {

    public static $tabla = 'lopez_historial_actividades';
    public static $columnasDB = [
        'id_usuario',
        'tipo_actividad',
        'modulo',
        'descripcion',
        'tabla_afectada',
        'id_registro_afectado',
        'ip_usuario',
        'datos_anteriores',
        'datos_nuevos'
    ];

    public static $idTabla = 'id_actividad';
    public $id_actividad;
    public $id_usuario;
    public $tipo_actividad;
    public $modulo;
    public $descripcion;
    public $tabla_afectada;
    public $id_registro_afectado;
    public $ip_usuario;
    public $fecha_actividad;
    public $datos_anteriores;
    public $datos_nuevos;

    public function __construct($args = []){
        $this->id_actividad = $args['id_actividad'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->tipo_actividad = $args['tipo_actividad'] ?? '';
        $this->modulo = $args['modulo'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->tabla_afectada = $args['tabla_afectada'] ?? '';
        $this->id_registro_afectado = $args['id_registro_afectado'] ?? null;
        $this->ip_usuario = $args['ip_usuario'] ?? '';
        $this->fecha_actividad = $args['fecha_actividad'] ?? null;
        $this->datos_anteriores = $args['datos_anteriores'] ?? '';
        $this->datos_nuevos = $args['datos_nuevos'] ?? '';
    }

    // Método estático para registrar actividades automáticamente
    public static function registrarActividad($id_usuario, $tipo_actividad, $modulo, $descripcion, $tabla_afectada = null, $id_registro_afectado = null, $datos_anteriores = null, $datos_nuevos = null) {
        try {
            $ip_usuario = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
            
            $historial = new self([
                'id_usuario' => $id_usuario,
                'tipo_actividad' => $tipo_actividad,
                'modulo' => $modulo,
                'descripcion' => $descripcion,
                'tabla_afectada' => $tabla_afectada,
                'id_registro_afectado' => $id_registro_afectado,
                'ip_usuario' => $ip_usuario,
                'datos_anteriores' => $datos_anteriores ? json_encode($datos_anteriores) : null,
                'datos_nuevos' => $datos_nuevos ? json_encode($datos_nuevos) : null
            ]);

            return $historial->crear();
        } catch (Exception $e) {
            // Si hay error al registrar historial, no afectar la operación principal
            error_log("Error registrando actividad: " . $e->getMessage());
            return false;
        }
    }
}