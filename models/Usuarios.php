<?php

namespace Model;
use Model\HistorialActividades;

class Usuarios extends ActiveRecord {

    public static $tabla = 'lopez_usuarios';
    public static $columnasDB = [
        'nombre_usuario',
        'password',
        'nombre_completo',
        'email',
        'telefono',
        'foto',  // Nueva columna agregada
        'id_rol',
        'activo'
    ];

    public static $idTabla = 'id_usuario';
    public $id_usuario;
    public $nombre_usuario;
    public $password;
    public $nombre_completo;
    public $email;
    public $telefono;
    public $foto;  // Nueva propiedad
    public $id_rol;
    public $activo;
    public $fecha_creacion;
    public $ultimo_acceso;

    public function __construct($args = []){
        $this->id_usuario = $args['id_usuario'] ?? null;
        $this->nombre_usuario = $args['nombre_usuario'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->nombre_completo = $args['nombre_completo'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->foto = $args['foto'] ?? '';  // Nueva propiedad inicializada
        $this->id_rol = $args['id_rol'] ?? '';
        $this->activo = $args['activo'] ?? 'T';
        $this->fecha_creacion = $args['fecha_creacion'] ?? null;
        $this->ultimo_acceso = $args['ultimo_acceso'] ?? null;
    }
}