<?php

namespace App\Providers;

use App\Models\Servicio;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		/**
		 * Utilizamos el componente sidebar para enviarle por defecto toda la información de la base de datos para recrear el menu
		 * de navegación según el rol y los modulos y servicios asignados a a este.
		 * */
		Facades\View::composer('componentes.sidebar', function (View $view) {
			$idrol = auth()->user()->idrol;
			$modulos = [];
			$servicios = Servicio::select('tb_servicios.*', 'tb_modulos.orden', 'tb_modulos.icono', 'tb_modulos.modulo', 'tb_modulos.estatus as m_estatus')
				->join('tb_rol_servicio', 'tb_servicios.idservicio', 'tb_rol_servicio.idservicio')
				->join('tb_modulos', 'tb_servicios.idmodulo', 'tb_modulos.idmodulo')
				->whereNull('tb_servicios.idservicio_raiz')
				->where('tb_rol_servicio.idrol', '=', $idrol)
				->orderBy('tb_modulos.orden', 'ASC')
				->get();
			foreach ($servicios as $servicio) {
				// Creamos en el primer nivel la información del módulo y dentro un nuevo arreglo vacío para guardar los servicios.
				if (!isset($modulos[$servicio->orden])) {
					$modulos[$servicio->orden] = [
						'idmodulo' => $servicio->idmodulo,
						'icono' => $servicio->icono,
						'modulo' => $servicio->modulo,
						'servicios' => [],
						'estatus' => $servicio->m_estatus,
					];
				}

				// Agregamos la info del servicio dentro del arreglo.
				$modulos[$servicio->orden]['servicios'][] = $servicio;
			}

			// Cargamos los datos en vista con el nombre de "modulos_servicios".
			$modulos = json_decode(json_encode($modulos, JSON_FORCE_OBJECT));
			$view->with('modulos_servicios', $modulos);
		});
	}
}
