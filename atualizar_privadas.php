<?php

include_once('config.php');

if(!isset($_COOKIE['user'])){
    header('location: login.php');
}
else{
    $user_name = $_COOKIE['user'];
}

$contato = $_POST['contato'];

$sql = mysqli_query($conn, "SELECT * FROM privado WHERE (contato = '$user_name' AND user_name = '$contato') OR (contato = '$contato' AND user_name = '$user_name') ORDER BY mensagem_id");

while ($row = mysqli_fetch_assoc($sql)) {
    $mensagem = $row['mensagem'];
    $dia = $row['data_hora'];
    $user = $row['user_name'];
    
    if($user == $user_name){
        echo '<div class="m"><fieldset class="me"><p class="mensagem">'.$mensagem.'</p><p class="data">'.$dia.'</p></fieldset></div>';
    }
    else{
        echo '<div class="m"><fieldset><p class="mensagem">'.$mensagem.'</p><p class="data">'.$dia.'</p></fieldset></div>';
    }
    
    $ex = mysqli_query($conn, "SELECT * FROM privado where (user_name='$user_name' and contato='$contato') or  (user_name='$contato' and contato='$user_name')");
    $qta =mysqli_num_rows($ex);

    mysqli_query($conn, "UPDATE visualizadas SET visualizadas = '$qta' WHERE user = '$user_name' AND contato = '$contato';");
}

?>