<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
	use HasFactory;

	const CREATED_AT = 'created';
	const UPDATED_AT = 'updated';

	protected $table = 'tb_usuarios';
	protected $primaryKey = 'id_usuario';
}
