<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
	use HasFactory;

	public $timestamps = false;
	protected $table = 'tb_visitas';
	protected $primaryKey = 'idvisita';
}
