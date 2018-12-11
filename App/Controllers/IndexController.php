<?php 

namespace App\Controllers;

# Recursos - estrutural
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action{

	public function index(){
		
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';

		// renderizando a página home
		$this->render('index');
	}
	public function inscreverse(){
		
		// realiza a verificação de cadastro
		$this->view->erroCadastro = false;

		// renderiza a página de cadastro
		$this->render('inscreverse');
	}
	public function registrar(){

		// instanciando a classe de usuário com a conexão - receber dados do formulários
		$usuario = Container::getModel('usuario');
	
		// passando valores do fumulários para classe usuario
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));
		
		// validando cadastro antes de registrar
		if($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0){
				
			// registrar novo usuário
			$usuario->salvar();

			// feedback para usuário
			$this->render('cadastro');

		}else {
			// auto-preenchimento de campos em caso de erro
			$this->view->user = array('nome' => $_POST['nome'],	'email' => $_POST['email'],	'senha' => $_POST['senha']);

			// realiza a verificação de cadastro
			$this->view->erroCadastro = true;

			// caso ocorra algum erro - renderizar a mesma página
			$this->render('inscreverse');
		}
	}
}