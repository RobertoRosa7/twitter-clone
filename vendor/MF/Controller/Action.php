<?php 

namespace MF\Controller;

abstract class Action{

	protected $view;

	public function __construct(){
		
		$this->view = new \stdClass();
	}
	protected function render($view, $layout = 'layout'){
		
		$this->view->page = $view;

		if(file_exists('../App/Views/'.$layout.'.phtml')){

			require_once '../App/Views/'.$layout.'.phtml';

		}else{
			
			$this->content();
		}
	}
	protected function content(){
		
		// caminho completo desta classe
		$classAtual = get_class($this);
		
		// filtro de substituição de app e controler por vazio
		$classAtual = str_replace('App\\Controllers\\', '', $classAtual);
		
		// filtro de substituição de controller por vazio
		$classAtual = strtolower(str_replace('Controller', '', $classAtual));
		
		// inclusão apenas do nome da página
		require_once '../App/Views/'.$classAtual.'/'.$this->view->page.'.phtml';
	}
}