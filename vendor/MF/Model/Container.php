<?php 

namespace MF\Model;

use App\Connection;

class Container{
	
	// objetivo aqui é retornar o modelo já instanciado, com conexão estabelecida
	public static function getModel($model){

		// fazendo a instanciação dinamica de classes
		$class = "\\App\\Models\\".ucfirst($model);

		$conn = Connection::getDb();

		return new $class($conn);
	}
}