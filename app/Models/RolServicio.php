<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolServicio extends Model
{
	use HasFactory;

	protected $primaryKey = null;
	public $incrementing = false;
	public $timestamps = false;
	protected $table = 'tb_rol_servicio';
}
