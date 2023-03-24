<?php

include_once('config.php');

$user_name = $_COOKIE['user'];

$contato = $_GET['id'];
$contato = str_replace("'", "", $contato);

$verificar = "SELECT * FROM contatos WHERE (user_name = '$user_name' AND user_name1 = '$contato') OR (user_name = '$contato' AND user_name1 = '$user_name')";
$resultado = mysqli_query($conn, $verificar) or die(mysqli_error($conn));

if(mysqli_num_rows($resultado) == 0){
    header("location: contatos.php");
    exit();
}

$veri = mysqli_query($conn, "SELECT * FROM visualizadas WHERE user='$user_name' AND contato='$contato'");

if(mysqli_num_rows($veri) < 1){
    mysqli_query($conn, "INSERT INTO visualizadas(user, contato, visualizadas) values('$user_name','$contato',0)");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafhy</title>
    <link rel="stylesheet" href="style/privado.css">
</head>
<body>
    <nav>
        <a href="contatos.php"><</a>
        <button id="btn1"><?php echo $contato;?></button>
    </nav>

    <div class="asideB" id="asideB">
        <aside id="aside">
            <ul>
                <li>Perfil de <?php echo $contato?></li>
                <?php 
                $perfill = mysqli_query($conn, "SELECT * FROM perfil where user='$contato'");
                if ($perfill) {
                    $perfil = mysqli_fetch_assoc($perfill);
                }
                ?>
                <li>
                    <div class="img-box"><img src="data:<?php echo $perfil['tipo_imagem']; ?>;base64,<?php echo base64_encode($perfil['conteudo_imagem']); ?>" alt="Imagem"></div>
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

    <div class="mensagens" id="mensagens">

    </div>

    <input class="mensagens2" id="mensagens2" type="hidden">

    <form id="enviar-mensagem" method="POST">
        <input type="text" id="mensagem" name="mensagem" placeholder="Mensagem" autocomplete="off">
        <input type="submit" value="Enviar" id="submit" name="submit" class="btn">
    </form>
    
</body>
<script src="script/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   var num_novas_mensagens;
   var num_mensagens;

   $(document).ready(function() {
    $('#enviar-mensagem').submit(function(event) {
        event.preventDefault();
        var mensagem = $('#mensagem').val();
        var contato = '<?php echo $contato; ?>';
        var user_name = '<?php echo $user_name; ?>';
        $.ajax({
        url: 'enviar_privadas.php',
        type: 'POST',
        data: {
            contato: contato,
            mensagem: mensagem,
            user_name: user_name
        },
        success: function(response) {
            $('#mensagem').val('');
        }
        });
    });
    
    function atualizarMensagens() {
        var contato = '<?php echo $contato; ?>';
        num_mensagens = $('#mensagens2 .mensagem').length;
        $.ajax({
        url: 'atualizar_privadas.php',
        type: 'POST',
        data: {
            contato: contato
        },
        success: function(response) {
            $('#mensagens2').html(response)
            num_novas_mensagens = $('#mensagens2 .mensagem').length - num_mensagens;
            if(num_novas_mensagens > 0){
                $('#mensagens').html(response)
                const div = document.querySelector('#mensagens');
                scroll();

            }
            else{
                
            }
        }
        });
        
    }

    function scroll(){
        const div = document.querySelector('#mensagens');
        div.scrollTop = div.scrollHeight;
    }

    setInterval(atualizarMensagens, 100);
    });

</script>

<style>
  nav:first-of-type a{
        width: 40px;
       transition: 0.2s ease-out;
    }

    nav:first-of-type a:hover {
        text-decoration: none;
        transform: translateX(-10px);
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
    color: #fff;
}

aside ul li{
    color: #fff;
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
</style>

</html>