<?php

include_once('config.php');

if(!isset($_COOKIE['user'])){
    header('location: login.php');
}
else{
    $user_name = $_COOKIE['user'];
}

if(isset($_POST['submit']) && !empty($_POST['chave'])){
    $chave = $_POST['chave'];
    $chave = str_replace(' ', '_', $_POST['chave']);

    $seeKey = "SELECT * FROM chats where chat_name = '$chave'";
    $result = mysqli_query($conn, $seeKey);

    if(mysqli_num_rows($result) == 0){
        mysqli_query($conn, "INSERT INTO chats VALUES (DEFAULT, '$chave', '1', '1')");
        mysqli_query($conn, "INSERT INTO acessos VALUES (DEFAULT, '$user_name', '$chave');");
        mysqli_query($conn, "CREATE TABLE $chave (mensagem_id INT AUTO_INCREMENT PRIMARY KEY, mensagem TEXT NOT NULL, data_hora VARCHAR(20) NOT NULL, user_name VARCHAR(100) NOT NULL)");
    }
    
    else{
        $seeKey = "SELECT * FROM acessos WHERE chat_name = '$chave' AND user_name = '$user_name'";
        $result = mysqli_query($conn, $seeKey);

        if(mysqli_num_rows($result) == 0){

            mysqli_query($conn, "INSERT INTO acessos VALUES (DEFAULT, '$user_name', '$chave');");

            $seeNumber = "SELECT user_number FROM chats where chat_name = '$chave'";
            $userNumber = mysqli_query($conn, $seeNumber);
            $row = mysqli_fetch_assoc($userNumber);
            $number_n = $row['user_number'];

            $number_n = $number_n + 1;

            mysqli_query($conn, "UPDATE chats SET user_number = '$number_n' WHERE chat_name = '$chave'");
        }
    }

    header("location: chat.php?id='$chave'");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafhy</title>
    <link rel="stylesheet" href="style/index.css">
</head>
<body>

    <?php echo $nav?> 

    <div class="asideB" id="asideB">
        <aside id="aside">
            <ul>
                <li>Seu perfil:</li>
                <?php 
                $perfill = mysqli_query($conn, "SELECT * FROM perfil where user='$user_name'");
                if ($perfill) {
                    $perfil = mysqli_fetch_assoc($perfill);
                }
                ?>
                <li>
                    <div class="img-box"><img src="data:<?php echo $perfil['tipo_imagem']; ?>;base64,<?php echo base64_encode($perfil['conteudo_imagem']); ?>" alt="Imagem"></div>
                    <?php echo $user_name;?>
                </li>
                
                <li>
                    <p><?php echo $perfil['descricao'];?></p>
                </li>
                
                <li> 
                    <p>Ultima visita:</p> 
                    <?php echo $perfil['online'];?>
                </li>
            </ul>
        </aside>
        <button class="LeftBar" id="btn2"></button>
    </div>

        <form action="" method="POST" id="setChave">
            <div class="conteudo">
                <label>Chave: </label>
                <input type="text" id="chave" name="chave" placeholder="public chat" autocomplete="off">
            </div>
            <input type="submit" id="submit" name="submit" value="Acessar">
        </form>

    <main>
        <div class="chats" id="chats">
            <?php 
                $solicit = "SELECT * FROM acessos WHERE user_name = '$user_name'";
                $result = mysqli_query($conn, $solicit);
                
                while($var = mysqli_fetch_assoc($result)){?>
                    <?php 
                    
                    $chat = $var['chat_name'];

                    $seenumber = "SELECT user_number FROM chats where chat_name = '$chat'";
                    $number = mysqli_query($conn, $seenumber);
                    if (!$number) {
                        die('Erro na consulta: ' . mysqli_error($conn));
                    }  
                    $row = mysqli_fetch_assoc($number);
                    $number_value = $row['user_number'];

                    echo "<div class=\"chat\"><a href=\"chat.php?id='$chat'\"><p>$chat</p> <p>$number_value</p></a></div>";?></p>
            <?php } ?>
        </div>
    </main>

    <?php echo $footer?>
     
</body>
<script src="script/script.js"></script>
<style>
        .img-box{
    width: 160px;
    height: 160px;
    object-fit: cover;
    overflow: hidden;
}

.img-box img{
    width: 100%;
    height: 100%;
}
    svg{
        width: 25px;
        height: 25px;
        color: var(--fonteColor);        
    }

    .contato a{
        display: flex;
        justify-content: space-between;
        padding-left: 20px;
        padding-right: 20px;
    }

    .qta{
        text-align: center;
        height: 25px;
        width: 25px;
        border-radius: 20px;
        border: solid 1px var(--color);
        padding-top: 1px;
        padding-left: 1px;
        color: var(--color);
    }

    .contato:hover .qta{
        border: solid 1px var(--fonteColor);
        color: var(--fonteColor);
    }
 
    .asideB{
    display: none;
    flex-direction: row-reverse;
    height: 100vh;
    position: fixed;
    right: -300px;
    width: 100vw;
    height: 100vh;
    z-index: 1001;
    overflow: hidden;
}


nav div button{
    width: auto;
    height: auto;
    border: none;
}

#btn2{
    height: 100%;
    width: calc(100vw - 300px);
    display: none;
    background-color: transparent;
    border: none;
}

aside{
    display: flex;
    flex-direction: column;
    width: 300px;
    backdrop-filter: blur(15px);
    background-color:  rgba(0, 0, 0, 0.397);
    border-left: var(--color) solid 1px;
}

aside ul{
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-around;
}

aside ul li{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

aside ul li a{
    text-decoration: none;
    color: #fff;
    font-size: 20px;
    font-family: Arial, Helvetica, sans-serif;
}


.fechar{
    animation: fechar .7s ease-in-out;
    right: -300px;
    top: 0px;
    position: fixed;
    display: flex;
    flex-direction: row-reverse;
    height: 100vh;
    z-index: 1001;
    overflow: hidden;
}

.abrir{
    animation: abrir .7s ease-in-out;
    right: 0px;
    top: 0px;
    position: fixed;
    display: flex;
    flex-direction: row-reverse;
    height: 100vh;
    z-index: 1001;
    overflow: hidden;
}

@keyframes abrir {
    0%{
        right: -300px;
    }
    100%{
        leright: 0px;
    }
}

@keyframes  fechar{
    0%{
        right: 0px;
    }
    100%{
        right: -300px;
    }
}

li p{
    margin: 20px;
}
</style>
</html>