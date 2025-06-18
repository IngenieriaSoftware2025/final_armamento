<?php

namespace Model;

class AsignacionesArmamento extends ActiveRecord {

    public static $tabla = 'lopez_asignaciones_armamento';
    public static $columnasDB = [
        'id_usuario',
        'id_modelo',
        'numero_serie',
        'fecha_asignacion',
        'fecha_devolucion',
        'estado',
        'observaciones',
        'activo',
        'usuario_creacion'
    ];

    public static $idTabla = 'id_asignacion';
    public $id_asignacion;
    public $id_usuario;
    public $id_modelo;
    public $numero_serie;
    public $fecha_asignacion;
    public $fecha_devolucion;
    public $estado;
    public $observaciones;
    public $activo;
    public $fecha_creacion;
    public $usuario_creacion;

    public function __construct($args = []){
        $this->id_asignacion = $args['id_asignacion'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->id_modelo = $args['id_modelo'] ?? '';
        $this->numero_serie = $args['numero_serie'] ?? '';
        $this->fecha_asignacion = $args['fecha_asignacion'] ?? '';
        $this->fecha_devolucion = $args['fecha_devolucion'] ?? null;
        $this->estado = $args['estado'] ?? 'ASIGNADO';
        $this->observaciones = $args['observaciones'] ?? '';
        $this->activo = $args['activo'] ?? 'T';
        $this->fecha_creacion = $args['fecha_creacion'] ?? null;
        $this->usuario_creacion = $args['usuario_creacion'] ?? null;
    }
}