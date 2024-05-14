<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instaladores extends Model
{
	use HasFactory;

	public $timestamps = false;
	protected $table = 'tb_instalacion_tecnicos';
	protected $primaryKey = 'iddetalle';
}
