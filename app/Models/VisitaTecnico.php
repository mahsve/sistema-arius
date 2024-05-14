<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaTecnico extends Model
{
	use HasFactory;

	public $timestamps = false;
	protected $table = 'tb_visitas_tecnicos';
	protected $primaryKey = 'iddetalle';
}
