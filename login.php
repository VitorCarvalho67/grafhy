<?php

include_once('config.php');

$erro = "";
$email = "";
$senha = "";

if(isset($_POST['submit'])){
    if(!empty($_POST['email']) && !empty($_POST['senha'])){
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM users where user_email = '$email' AND user_pass = '$senha'";

        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 0){
            $erro = "ERRO: email ou senha inválidos";
        }
        else{
            $resultado = mysqli_query($conn, "SELECT user_name FROM users WHERE user_email = '$email' AND user_pass = '$senha'");
            $usuario = mysqli_fetch_assoc($resultado);
            
            setcookie("user", $usuario['user_name'], time() + 300600); 
            header('location: index.php');
            $erro = "";
        }
    }
    else{
        $erro = "Preencha todos os campos!";
    }

    $senha = "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafhy</title>
    <link rel="stylesheet" href="style/login.css">
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

    <main>
        <form action="" method="POST" id="login">
            <legend>LOGIN</legend>
            <div class="conteudo">
                <input type="email" id="email" name="email" placeholder="E-mail" autocomplete="off" value="<?php echo $email;?>">
            </div>
            <div class="conteudo">
                <input type="password" id="senha" name="senha" placeholder="Senha" autocomplete="off" value="<?php echo $senha;?>">
            </div>

            <div class="erro"><?php echo $erro?></div>
    
            <input type="submit" id="submit" name="submit" value="Login" autocomplete="off">

            <div class="cadastre">
                <a href="cadastro.php" id="c">Cadastre-se</a>
            </div>
        </form>
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