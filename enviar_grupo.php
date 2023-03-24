<?php

include_once('config.php');

if(!isset($_COOKIE['user'])){
    exit;
}
else{
    $user_name = $_COOKIE['user'];
}

$mensagem = $_POST['mensagem'];
$grupo = $_POST['grupo'];

if(isset($_POST['mensagem'])){
    $mensagem = $_POST['mensagem'];
    $dia = date("Y-m-d H:i:s");

    if(!empty($mensagem)){
        mysqli_query($conn, "INSERT INTO mensagem_grupo VALUES (DEFAULT, '$mensagem', '$dia', '$grupo', '$user_name');");
    }
}
?>