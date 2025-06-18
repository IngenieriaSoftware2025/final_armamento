<?php

namespace Model;

class Marcas extends ActiveRecord {

    public static $tabla = 'lopez_marcas';
    public static $columnasDB = [
        'nombre_marca',
        'descripcion',
        'usuario_creacion',
        'activo'
    ];

    public static $idTabla = 'id_marca';
    public $id_marca;
    public $nombre_marca;
    public $descripcion;
    public $usuario_creacion;
    public $activo;
    public $fecha_creacion;

    public function __construct($args = []){
        $this->id_marca = $args['id_marca'] ?? null;
        $this->nombre_marca = $args['nombre_marca'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->usuario_creacion = $args['usuario_creacion'] ?? '';
        $this->activo = $args['activo'] ?? 'T';
        $this->fecha_creacion = $args['fecha_creacion'] ?? null;
    }
}