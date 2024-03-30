<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
	use HasFactory;

	public $timestamps = false; // CREATED y UPDATED deshabilitado.

	protected $table = 'tb_zonas';
	protected $primaryKey = 'idzona';
}
