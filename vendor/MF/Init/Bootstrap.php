<?php

namespace MF\Init;

abstract class Bootstrap {

	private $routes;
	
	abstract protected function initRoutes();

	public function __construct(){
		// inicializando na construção do objeto a chamada da função initRooutes
		$this->initRoutes();

		// descobrindo qual é a url atual retorna o path acessado pelo usuário
		$this->run($this->getUrl());
	}
	public function getRoutes(){
		return $this->routes;
	}
	public function setRoutes(array $routes){
		$this->routes = $routes;
	}
	/*
	* Quais são as rotas que a aplicação irá possuir
	*/
	protected function run($url){
		// percorrendo a array da URL para recuperar a url como array
		foreach ($this->getRoutes() as $key => $routes) {
			
			// se existir página igual definido pelo route
			if($url == $routes['route']){
			
				// criando dinamicamente o nome da classe que será instanciada
				$class = "App\\Controllers\\".ucfirst($routes['controller']);

				// instanciar a classe \App\Controllers\IndexController
				$controller = new $class;

				// disparando os métodos - index | sobreNos
				$action = $routes['action'];

				$controller->$action();
			}
		}
	}
	protected function getUrl(){
		/*
		* todos os detalhes do servidor da aplicação
		* REQUEST_URI = índice do array server que traz a raiz da diretório
		* parse_url = recebe e interpreta URL e retorna seus componentes
		* PHP_URL_PATH = traz uma string relativa ao path
		* @return Array()
		*/
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}
}