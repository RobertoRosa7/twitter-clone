<?php 

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model{
	
	private $id;
	
	private $nome;
	
	private $email;
	
	private $senha;

	// recuperar e modificar atributos privados
	public function __get($attr){
	
		return $this->$attr;
	
	}
	
	public function __set($attr, $value){
	
		$this->$attr = $value;
	
	}

	// salvar
	public function salvar(){
		
		$query = "insert into usuarios(nome,email,senha)values(:nome,:email,:senha)";
		
		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':nome', $this->__get('nome'));
		
		$stmt->bindValue(':email', $this->__get('email'));
		
		$stmt->bindValue(':senha', $this->__get('senha')); //md5() -> 32 caracteres
		
		$stmt->execute();

		return $this;
	}

	// validar se um cadastro pode ser feito
	public function validarCadastro(){
		
		$valido = true;

		if(strlen($this->__get('nome')) < 3){
		
			$valido = false;
		}
		
		if(strlen($this->__get('email')) < 3){
		
			$valido = false;
		}
		
		if(strlen($this->__get('senha')) < 3){
		
			$valido = false;
		}
		
		return $valido;
	}

	// recuperar um usuário por e-mail
	public function getUsuarioPorEmail(){
		
		$query = 'select nome, email from usuarios where email = :email';
		
		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':email', $this->__get('email'));
		
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	public function autenticar(){
		
		$query = 'select id, nome, email, senha from usuarios where email = :email and senha = :senha';
		
		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':email', $this->__get('email'));
		
		$stmt->bindValue(':senha', $this->__get('senha'));
		
		$stmt->execute();
		
		$usuario =  $stmt->fetch(\PDO::FETCH_ASSOC);

		// verificar se os campos não estão vazio
		if($usuario['id'] != '' && $usuario['nome'] != ''){
		
			$this->__set('id', $usuario['id']);
		
			$this->__set('nome', $usuario['nome']);
		
		}

		return $this;
	}
	public function getAll(){
		
		// pesquisar usuários pela ocorrência do nome mais id de usuarios seguindo
		$query = "
			select 
				u.id, 
				u.nome, 
				u.email, 
				(
					select
						count(*)
					from
						usuarios_seguidores as us
					where
						us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
				) as seguindo_sn 
			from 
				usuarios as u
			where 
				u.nome 
			like :nome and u.id != :id_usuario";

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':nome', '%'.$this->__get('nome').'%');

		$stmt->bindValue(':id_usuario', $this->__get('id'));

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}
	public function seguirUsuario($id_usuario_seguindo){

		$query = "insert into usuarios_seguidores(id_usuario,id_usuario_seguindo)

		values(:id_usuario, :id_usuario_seguindo)";

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':id_usuario', $this->__get('id'));

		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);

		$stmt->execute();

		//retorno true para apenas saber se foi inserido
		return true;

	}

	public function deixarSeguirUsuario($id_usuario_seguindo){

		$query = "delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";
		
		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':id_usuario', $this->__get('id'));
		
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);

		$stmt->execute();

		return true;

	}

	// informações do usuário
	public function getInfoUsuario(){
		
		$query = "select nome from usuarios where id = :id_usuario";
		
		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		
		$stmt->execute();

		// retorno apenas de um registro
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	// total de tweets
	public function totaTweets(){
		
		$query = "select count(*) as total_tweets from tweets where id_usuario = :id_usuario";
		
		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		
		$stmt->execute();

		// retorno apenas de um registro
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	// total de seguintes
	public function getTotalSeguindo(){
		
		$query = "select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario";
		
		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		
		$stmt->execute();

		// retorno apenas de um registro
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	// total de seguidores
	public function getTotalSeguidores(){
		
		$query = "select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario";
		
		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		
		$stmt->execute();

		// retorno apenas de um registro
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
}