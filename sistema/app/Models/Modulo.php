<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
	use HasFactory;

	const CREATED_AT = 'created';
	const UPDATED_AT = 'updated';

	protected $table = 'tb_modulos';
	protected $primaryKey = 'idmodulo';
}
