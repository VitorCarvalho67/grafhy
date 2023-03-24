<?php

include_once('config.php');

if(!isset($_COOKIE['user'])){
    header('location: login.php');
}
else{
    $user_name = $_COOKIE['user'];
}

$grupo = $_POST['grupo'];

$sql = mysqli_query($conn, "SELECT * FROM mensagem_grupo WHERE grupo = '$grupo' ORDER BY mensagem_id");

if (!$sql) {
    die('Erro na consulta: ' . mysqli_error($conn));
}  

while ($row = mysqli_fetch_assoc($sql)) {
    $mensagem = $row['mensagem'];
    $dia = $row['data_hora'];
    $user = $row['user_name'];
    
    if($user == $user_name){
        echo '<div class="m"><fieldset class="me"><p class="mensagem">'.$mensagem.'</p><p class="data">'.$dia.'</p></fieldset></div>';
    }
    else{
        echo '<div class="m"><fieldset><label class="remetente">'.$user .'</label><p class="mensagem">'.$mensagem.'</p><p class="data">'.$dia.'</p></fieldset></div>';
    }
    
}

?>