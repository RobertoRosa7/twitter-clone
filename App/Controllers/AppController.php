<?php 

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{

	public function timeline(){
		
		// validação de autenticação
		$this->validaAutenticacao();

		// instanciação 
		$tweet = Container::getModel('Tweet');
		$usuario = Container::getModel('Usuario');

		// recuperando tweets somente do usuário da sessão
		$tweet->__set('id_usuario', $_SESSION['id']);
		$usuario->__set('id', $_SESSION['id']);

		// recuperação dos tweets
		$this->view->tweets 			= $tweet->getAll();
		$this->view->info_usuario		= $usuario->getInfoUsuario();
		$this->view->total_tweets 		= $usuario->totaTweets();
		$this->view->total_seguindo 	= $usuario->getTotalSeguindo();
		$this->view->total_seguidores	= $usuario->getTotalSeguidores();

		// passandos todos os tweets do usuário logado para página timeline
		//$this->view->tweets = $tweets;
		//$this->view->usuarios = $usuarios;

		// renderização da página timeline
		$this->render('timeline');
	}
	public function tweet(){
		// validação da página
		$this->validaAutenticacao();
		
		// instanciando a classe com conexão
		$tweet = Container::getModel('Tweet');
	
		// configurando tweet vindo do poste para seu atributo da classe
		$tweet->__set('tweet', $_POST['tweet']);

		// recuperando id do usuário da sessão
		$tweet->__set('id_usuario', $_SESSION['id']);

		// registrando tweet no banco de dados
		$tweet->salvar();

		// redirecionando a página para atualizar
		header('Location: /timeline');
		
	}

	public function validaAutenticacao(){
		// recuperando a sessão do usuário 
		session_start();

		// validando a sessão se existir id 
		if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
			
			// forçando redirecionamento para página home com feedback ao usuário
			header('Location: /?login=erro');
		}
	}

	public function quemSeguir(){

		// validação da página
		$this->validaAutenticacao();

		$user = Container::getModel('Usuario');

		// recuperando tweets somente do usuário da sessão
		$user->__set('id', $_SESSION['id']);

		// recuperação dos tweets
		$this->view->info_usuario		= $user->getInfoUsuario();
		$this->view->total_tweets 		= $user->totaTweets();
		$this->view->total_seguindo 	= $user->getTotalSeguindo();
		$this->view->total_seguidores	= $user->getTotalSeguidores();

		// recuperando pesquisa via GET do formulário
		$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

		// Array vazio para recuperar dados do escopo if
		$usuarios = [];

		// pesquisando somente se existir valor no campo de pesquisa
		if($pesquisarPor != ''){
			
			// instanciando a classe usuario
			$usuario = Container::getModel('Usuario');
			
			// passando a busca para a classe usuario
			$usuario->__set('nome', $pesquisarPor);

			// recuperando id do usuario da sessão - para não seguir a si mesmo
			$usuario->__set('id', $_SESSION['id']);	
			
			// recuperando todos os usuários do bando de dados para listagem pesquisar
			$usuarios = $usuario->getAll();

		}
		// passados os usuarios da pesquisa para poder ser renderizados na view
		$this->view->usuarios = $usuarios;

		// renderização da página quem seguir
		$this->render('quemSeguir');
	}
	public function acao(){
	
		// validação da página
		$this->validaAutenticacao();

		// recuperando ação seguir ou deixar de seguir
		$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
		
		// recuperando id do usuario que queremos seguir ou deixar
		$id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

		// instanciando a classe objeto com conexão
		$usuario = Container::getModel('Usuario');
		
		// recuperando id do usuaŕio da sessão
		$usuario->__set('id', $_SESSION['id']);

		// tomando a decisão de seguir ou deixar de seguir
		if($acao == 'seguir'){
		
			// recuperar objeto usuario passando id do usuario seguindo
			$usuario->seguirUsuario($id_usuario_seguindo);

		}else if($acao == 'deixar_seguir'){
			
			// recuperar objeto usuario passando id do usuario seguindo
			$usuario->deixarSeguirUsuario($id_usuario_seguindo);
		}

		header('Location: /quem_seguir');
	}

	public function deleteTweet(){

		$this->validaAutenticacao();

		$id_tweet = isset($_GET['remove']) ? $_GET['remove'] : '';

		$tweet = Container::getModel('Tweet');

		$tweet->__set('id', $id_tweet);
		
		$tweet->removeTweet();

		header('Location: /timeline');
	}
}