<?php

	$user = "root";
	$pass = "";
	$dbname = "basescc";
	$pdo = new PDO("mysql:host=localhost;dbname=".$dbname.";charset=UTF8", $user, $pass);

	
	switch($_SERVER['REQUEST_METHOD']){
		case "GET":	
			if(isset($_GET['id'])){
				read_projet($_GET['id']);
			}else{		
				$projet = isset($_GET['projet']) ? $_GET['projet'] : '';
				$vote = isset($_GET['vote']) ? $_GET['vote'] : '';
				$universite = isset($_GET['universite']) ? $_GET['universite'] : '';
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				list_projets($projet, $vote, $universite, $page);		
			}
			break;
		case "POST":
			$projet = $_POST;
			create_projet($projet);
			break;
		case "PUT":
			$info = json_decode(file_get_contents("php://input"),true);
			edit_projet($_GET['id'],$info['vote']);
			break;
		case "DELETE":
			delete_projet($_GET['id']);	
			break;
	}
	
	
	function list_projets($projet, $vote, $universite, $page=1){
		global $pdo;
		$query = "SELECT id, projet, universite FROM projet_universite WHERE ";
		$data = [];
		
		if($projet){
			$query .= "projet = ? AND ";
			array_push($data,$projet);
		}
		if($vote){
			$query .= "vote >= ? AND ";
			array_push($data,$vote);
		}if($universite){
			$query .= "universite = ? AND ";
			array_push($data,$universite);
		}
		$start = ($page-1)*20;
		//$number = 20;
		//je mets ce 1 = 1 juste pour gérer plus facilement l'opérateur AND
		$query .= "1 = 1 ORDER BY projet LIMIT {$start}, 20";
		
		if($projet || $vote || $universite){
			$statement = $pdo->prepare($query);
			$statement->execute($data);
			$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		}else{
			$result = $pdo->query($query);
			$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		echo json_encode($rows);
	}
	
	function read_projet($id){
		global $pdo;
		$query = "SELECT * FROM projet_universite WHERE id = ?";
		$statement = $pdo->prepare($query);
		$statement->execute([$id]);
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		if(sizeof($rows)>0)
			echo json_encode($rows);
		else
			echo "404";
	}
	
	function create_projet($projet){
		echo "Ajoute un projet";
		print_r($projet);
	}
	
	function edit_projet($id, $vote){
		global $pdo;
		$query = "UPDATE projet_universite SET vote = ?WHERE id = ?";
		$statement = $pdo->prepare($query);
		$statement->execute([$vote, $id]);
	}
	
	function delete_projet($id){
		global $pdo;
		$query = "DELETE FROM projet_universite WHERE id = ?";
		$statement = $pdo->prepare($query);
		$statement->execute([$id]);
	}
	