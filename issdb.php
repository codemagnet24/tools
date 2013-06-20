<?php

$host	= '';
$dbs	= '';
$user	= '';
$pass	= '';

//$db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

try{
  $db = new PDO("mysql:host=$host;dbname=$dbs", $user, $pass);
}catch(Exception $e){
  echo 'UNABLE TO CONNECT DB';
}


function fetchAll($sql,$db){
	$get = $db->prepare($sql);
	$get->execute();
	return $get->fetchAll(PDO::FETCH_ASSOC);
}
function fetch($sql,$db){
	$get = $db->prepare($sql);
	$get->execute();
	return $get->fetch(PDO::FETCH_ASSOC);
}
function rowCount($sql,$db){
	$get = $db->prepare($sql);
	$get->execute();
	return $get->rowCount();
}



?>
