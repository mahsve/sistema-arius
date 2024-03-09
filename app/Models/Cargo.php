<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
	use HasFactory;

	const CREATED_AT = 'created';
	const UPDATED_AT = 'updated';

	protected $table = 'tb_cargos';
	protected $primaryKey = 'id_cargo';
}
