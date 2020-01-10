<?php

	$user = "root";
	$pass = "";
	$dbname = "basescc";
	$pdo = new PDO("mysql:host=localhost;dbname=".$dbname.";charset=UTF8", $user, $pass);

	switch($_SERVER['REQUEST_METHOD']){
		case "GET":	
			$page = isset($_GET['page']) ? $_GET['page'] : 1;
			list_universities($page);		
			break;
	}
	
	function list_universities($page=1){
		global $pdo;
		//improve
		//$data = [];
		
		$start = ($page-1)*20;
		$query = "SELECT DISTINCT universite FROM projet_universite ORDER BY universite ASC LIMIT :start, 20";
		
		//$number = 20;
		//echo $query;
		//print_r($data);
		
		$statement = $pdo->prepare($query);
		$statement->bindValue(':start',$start, PDO::PARAM_INT);
		$statement->execute() or die(print_r($fetchPictures->errorInfo()));
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		print_r($rows);
		/*
		$result = $pdo->query($query);
		$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		*/
		echo json_encode($rows);
	}