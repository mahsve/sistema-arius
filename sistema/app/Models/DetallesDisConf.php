<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesDisConf extends Model
{
	use HasFactory;

	public $timestamps = false; // CREATED y UPDATED deshabilitado.

	protected $table = 'tb_detalles_conf';
	protected $primaryKey = null;
	public $incrementing = false;
}
