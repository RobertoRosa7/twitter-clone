<?php 
/*
* Primeira coisa é estabelecer o namespace 
* Especificação PSR-4 - um script dentro de um diretório esteja em um namespace compativel com o diretório
*/
namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap{

	protected function initRoutes(){
	
		// indexController
		$routes['home']			= ['route' => '/', 'controller'	=> 'indexController','action' => 'index'];
		$routes['inscreverse']	= ['route' => '/inscreverse','controller' => 'indexController','action' => 'inscreverse'];
		$routes['registrar']	= ['route' => '/registrar','controller' => 'indexController','action' => 'registrar'];
		
		// authController
		$routes['autenticar']	= ['route' => '/autenticar','controller' => 'AuthController','action' => 'autenticar'];
		$routes['sair']	= ['route' => '/sair','controller' => 'AuthController','action' => 'sair'];
		
		// appController
		$routes['timeline']		= ['route' => '/timeline','controller' => 'AppController','action' => 'timeline'];
		$routes['tweet']		= ['route' => '/tweet','controller' => 'AppController','action' => 'tweet'];
		$routes['quem_seguir']	= ['route' => '/quem_seguir','controller' => 'AppController','action' => 'quemSeguir'];
		$routes['acao']			= ['route' => '/acao','controller' => 'AppController','action' => 'acao'];
		$routes['delete_tweet']	= ['route' => '/delete_tweet','controller' => 'AppController','action' => 'deleteTweet'];
		
		// configura o array para tomada de decisão na contrução do objeto		
		$this->setRoutes($routes);
	}
}