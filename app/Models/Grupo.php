<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['grupo'];

    public function alimentos()
    {
        return $this->hasMany(Alimento::class);
    }
}
