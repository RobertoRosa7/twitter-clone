<?php 

namespace App;

class Connection{
	
	public static function getDb(){

		try{
			//usar barra invertida quando usamos namespace e classe nativas do PHP
			$conn = new \PDO("mysql:host=localhost;dbname=twitter_clone;charset=utf8","kakashi","123765");
			
			return $conn;

		}catch(\PDOExpection $e){
			// tratar o erro de alguma forma
			echo '<p>'.$e->getMessage().'</p>';
		}
	}
}