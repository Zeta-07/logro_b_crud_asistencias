<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

#[Table('asistencias')]
#[Fillable(['estudiante', 'materia', 'fecha', 'estado', 'observaciones'])]

class Asistencia extends Model
{
    //
}
