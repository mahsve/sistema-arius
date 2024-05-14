<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoMonitoreo extends Model
{
	use HasFactory;

	public $timestamps = false;
	protected $table = 'tb_reportes_detalles';
	protected $primaryKey = 'iddetalle';
}
