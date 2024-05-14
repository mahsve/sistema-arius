<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

trait RegistroBitacoraControlador
{
	private function getIP()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	private function getBrowser()
	{
		$u_agent	= $_SERVER['HTTP_USER_AGENT'];
		$bname		= 'Desconocido';
		$platform	= 'Desconocido';
		$version	= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'Linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'Mac';
		} elseif (preg_match('/Windows NT 10.0/i', $u_agent)) {
			$platform = 'Windows 10';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'Windows';
		}

		//should I add support for 128 bit?? :D
		if (preg_match('/WOW64/i', $u_agent) or preg_match('/Win64/i', $u_agent)) {
			$bit = '64';
		} else {
			$bit = '32';
		}

		// Next get the name of the useragent yes seperately and for good reason
		if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} elseif (preg_match('/Edge/i', $u_agent)) {
			$bname = 'Microsoft Edge';
			$ub = "Edge";
		} elseif (preg_match('/Firefox/i', $u_agent)) {
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} elseif (preg_match('/Chrome/i', $u_agent)) {
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} elseif (preg_match('/Safari/i', $u_agent)) {
			$bname = 'Apple Safari';
			$ub = "Safari";
		} elseif (preg_match('/Opera/i', $u_agent)) {
			$bname = 'Opera';
			$ub = "Opera";
		} elseif (preg_match('/Netscape/i', $u_agent)) {
			$bname = 'Netscape';
			$ub = "Netscape";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}

		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
				$version = $matches['version'][0];
			} else {
				$version = $matches['version'][1];
			}
		} else {
			$version = $matches['version'][0];
		}

		// check if we have a number
		if ($version == null || $version == "") {
			$version = "?";
		}

		// Retornamos una cadena de caracteres con la información conseguida.
		return $bname . " " . $version . " en " . $platform . " (" . $bit . " Bit)";
	}

	public function guardar_registro_bitacora(string $operacion, string $descripcion)
	{
		$bitacora = new Bitacora();
		$bitacora->fecha = date('Y-m-d H:i:s');
		$bitacora->ip = $this->getIP();
		$bitacora->navegador = $this->getBrowser();
		$bitacora->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$bitacora->operacion = $operacion;
		$bitacora->descripcion = $descripcion;
		$bitacora->idusuario = auth()->user()->idusuario;
		$bitacora->save();
	}
}
