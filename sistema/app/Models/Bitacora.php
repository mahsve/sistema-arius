<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
	use HasFactory;

	public $timestamps = false;
	protected $table = 'tb_bitacora';
	protected $primaryKey = 'idbitacora';
}
