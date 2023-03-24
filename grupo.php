<?php

include_once('config.php');

$user_name = $_COOKIE['user'];

$grupo = $_GET['id'];
$grupo = str_replace("'", "", $grupo);

$verificar = "SELECT * FROM acessos_grupos WHERE grupo = '$grupo' AND user_name = '$user_name'";
$resultado = mysqli_query($conn, $verificar) or die(mysqli_error($conn));

if(mysqli_num_rows($resultado) == 0){
    header("location: contatos.php");
    exit();
}

if(isset($_POST['convidar'])){
    if(!empty($_POST['convidado'])){
    
        $convidado = $_POST['convidado'];

    if($user_name != $convidado){
        $grupo = $_GET['id'];
        $grupo = str_replace("'", "", $grupo);
    
        $userNameReceiv = $_POST['convidado'];
        $userNameSend = $_COOKIE['user'];
        $data = date("Y-m-d H:i:s");
    
        $contato = mysqli_query($conn, "SELECT * FROM users WHERE user_name = '$user_name'");
        $contato2 = mysqli_query($conn, "SELECT * FROM users WHERE user_name = '$convidado'");
    
        if(mysqli_num_rows($contato) > 0 && mysqli_num_rows($contato2) > 0){
            $verificar = mysqli_query($conn, "SELECT * FROM convites WHERE user_name_recebe = '$userNameReceiv' AND grupo = '$grupo'");
    
            if(mysqli_num_rows($verificar) > 0){
                $erro2 = "ERRO: Já existe um convite para ".$userNameReceiv." de ". $grupo;
            }
            else{
                $verificar = mysqli_query($conn, "SELECT * FROM acessos_grupos WHERE user_name = '$userNameReceiv' AND grupo = '$grupo'");
                if(mysqli_num_rows($verificar) > 0){
                    $erro2 = "ERRO: ".$userNameReceiv." já está nesse grupo.";
                }
                else{
                    if(!empty($userNameReceiv) && !empty($userNameSend) && !empty($grupo))
                    mysqli_query($conn, "INSERT INTO convites VALUES (DEFAULT, '$userNameReceiv', '$userNameSend', '$grupo', '$data')");
                    $erro2 = "";
                }
            }
        }
        else{
            $erro2 = "ERRO: Usuário não encontrado";
        }
    }
}
else{
    $erro2 = "Insira um nome!";
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafhy</title>
    <link rel="stylesheet" href="style/grupo.css">
</head>
<body>
    <nav>
        <a href="contatos.php"><</a>
        <div>
            <?php 
            
            $primeiro = mysqli_query($conn, "SELECT user_name FROM acessos_grupos WHERE grupo = '$grupo' LIMIT 1");
            $primeiro = mysqli_fetch_assoc($primeiro)['user_name'];

            if($primeiro == $user_name){
                echo "<p id=\"con\">Convidar</p>";
            }

            ?>
            <p><?php echo str_replace("'", "", $grupo);?></p>
        </div>
    </nav>

    <?php 
        $primeiro = mysqli_query($conn, "SELECT user_name FROM acessos_grupos WHERE grupo = '$grupo' LIMIT 1");
        $primeiro = mysqli_fetch_assoc($primeiro)['user_name'];

        if($primeiro == $user_name){
            echo "<div id=\"newGroup\">
                    <div id=\"a\"></div>
                    <form action=\"\" method=\"POST\" id=\"criar\" class=\"setChave\" name=\"criar\">
                        <div class=\"conteudo\">
                            <label>Convidar: </label>
                            <input type=\"text\" id=\"convidado\" name=\"convidado\" placeholder=\"UserName\" autocomplete=\"off\">
                        </div>
                        <div class=\"erro\"><?php if(isset(\$erro2)){echo \$erro2;}?></div>
                        <input type=\"submit\" id=\"submit2\" name=\"convidar\" value=\"Enviar convite\">
                    </form>
                </div>";

        echo "<script>
                document.getElementById('con').onclick = () => {
                    newGroup.style.display = \"flex\";
                }

                document.getElementById('a').onclick = () => {
                    newGroup.style.display = \"none\";
                }
              </script>";
        }

    ?>

    <div class="mensagens" id="mensagens">

    </div>

    <input class="mensagens2" id="mensagens2" type="hidden">

    <form id="enviar-mensagem" method="POST">
        <input type="text" id="mensagem" name="mensagem" placeholder="Mensagem" autocomplete="off">
        <input type="submit" value="Enviar" id="submit" name="submit" class="btn">
    </form>
    
</body>
<script src="script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var num_novas_mensagens;
var num_mensagens;

$(document).ready(function() {
    $('#enviar-mensagem').submit(function(event) {
        event.preventDefault();
        var mensagem = $('#mensagem').val();
        var grupo = '<?php $grupo = $_GET['id'];
        $grupo = str_replace("'", "", $grupo); echo $grupo; ?>';
        var user_name = '<?php echo $user_name; ?>';
        $.ajax({
        url: 'enviar_grupo.php',
        type: 'POST',
        data: {
            grupo: grupo,
            mensagem: mensagem,
            user_name: user_name
        },
        success: function(response) {
            $('#mensagem').val('');
            atualizarMensagens();
            scroll();
        }
        });
    });
    
    function atualizarMensagens() {
        var grupo = '<?php $grupo = $_GET['id'];
        $grupo = str_replace("'", "", $grupo); echo $grupo; ?>';

        num_mensagens = $('#mensagens2 .mensagem').length;

        $.ajax({
        url: 'atualizar_grupo.php',
        type: 'POST',
        data: {
            grupo: grupo
        },
        success: function(response) {
            $('#mensagens2').html(response)
            num_novas_mensagens = $('#mensagens2 .mensagem').length - num_mensagens;
            if(num_novas_mensagens > 0){
                $('#mensagens').html(response)
                const div = document.querySelector('#mensagens');
                div.scrollTop = div.scrollHeight;

                console.log("Atualizei.");
            }
            else{
                console.log("Nada novo.");
            }
        }
        });
        
    }
    setInterval(atualizarMensagens, 100);

    function scroll(){
        const div = document.querySelector('#mensagens');
        div.scrollTop = div.scrollHeight;
    }
    });

</script>
<style>
    #newGroup{
        position: fixed;
        width: 100vw;
        height: 100vh;
        display: none;
        background-color: rgb(0, 0, 0, 0.350);
        z-index: 2000;
        justify-content: center;
        align-items: center;
    }

    #a{
        position: fixed;
        top: 0;
        width: 100%;
        height: 100%;
        display: flex;
    }

    .setChave{
        position: fixed;
        top: calc(50vh - 100px);
        width: 400px;
        max-width: 100vw;
        height: 200px;
        padding: 10px;
        background-color: var(--color2);
        border: 1px solid var(--color);
        border-radius: 10px;
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        flex-direction: column;
    }

    .setChave input{
        border: none;
        border-bottom: 1px solid var(--color);
        outline: none;
        width: 80%;
        background-color: transparent;
        padding-left: 3px;
        color: var(--color);
        z-index: 1000;
        padding: 5px;
        margin: 5px;
    }

    .conteudo{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 90%;
    }

    label{
        font-size: 15px;
    }

    .setChave input[type="submit"]{
        border: 1px solid var(--color);
    }

    @media screen and (max-width: 500px) {
        .newGroup{
            width: 100vw;
            border-radius: 0px;
            border-left: none;
            border-right: none;
        }
    }

    nav:first-of-type a{
        width: 40px;
       transition: 0.2s ease-out;
    }

    nav:first-of-type a:hover {
        text-decoration: none;
        transform: translateX(-10px);
    }

    .mensagens{
        margin-top: 10vh;
        margin-bottom: 15vh;
    }

    
</style>
</html>