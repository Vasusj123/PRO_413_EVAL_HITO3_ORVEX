<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudSoporte extends Model
{
    protected $table = 'solicitudes_soporte';

    public const PRIORIDADES = ['Baja', 'Media', 'Alta'];
    public const ESTADOS = ['Pendiente', 'En proceso', 'Resuelta'];

    protected $fillable = [
        'solicitante',
        'departamento',
        'descripcion',
        'prioridad',
        'estado',
    ];

    protected $attributes = [
        'estado' => 'Pendiente',
    ];
}
