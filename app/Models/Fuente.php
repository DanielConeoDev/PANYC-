<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fuente extends Model
{
    use HasFactory;

    protected $fillable = ['fuente', 'pais', 'año', 'url'];

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function alimentos()
    {
        return $this->hasMany(Alimento::class);
    }
}
