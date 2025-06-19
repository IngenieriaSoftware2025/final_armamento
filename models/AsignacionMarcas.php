<?php

namespace Model;

class AsignacionMarcas extends ActiveRecord {

    public static $tabla = 'lopez_asignacion_marcas';
    public static $columnasDB = [
        'id_usuario',
        'id_marca',
        'usuario_asignador',
        'observaciones',
        'activo'
    ];

    public static $idTabla = 'id_asignacion';
    public $id_asignacion;
    public $id_usuario;
    public $id_marca;
    public $fecha_asignacion;
    public $activo;
    public $usuario_asignador;
    public $observaciones;

    public function __construct($args = []){
        $this->id_asignacion = $args['id_asignacion'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->id_marca = $args['id_marca'] ?? '';
        $this->fecha_asignacion = $args['fecha_asignacion'] ?? null;
        $this->activo = $args['activo'] ?? 'T';
        $this->usuario_asignador = $args['usuario_asignador'] ?? '';
        $this->observaciones = $args['observaciones'] ?? '';
    }
}