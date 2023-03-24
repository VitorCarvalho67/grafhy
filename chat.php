<?php

include_once('config.php');

if(!isset($_COOKIE['user'])){
    header('location: login.php');
}
else{
    $user_name = $_COOKIE['user'];
}

$chave = $_GET['id'];
$chave = str_replace('\'', "", $chave);

$_SESSION['id'] = $chave;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafhy</title>
    <link rel="stylesheet" href="style/chat.css">
</head>
<body>

    <nav>
        <a href="index.php"><</a>
        <p><?php echo $chave;?></p>
    </nav>

    <div class="mensagens" id="mensagens">
      
    </div>

    <input class="mensagens2" id="mensagens2" type="hidden">

    <form id="enviar-mensagem" method="POST">
        <input type="text" id="mensagem" name="mensagem" placeholder="Mensagem" autocomplete="off"></input>
        <input type="submit" value="Enviar" id="submit" name="submit" class="btn">
    </form>
    
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
var num_novas_mensagens;
var num_mensagens;

$(document).ready(function() {
  $('#enviar-mensagem').submit(function(event) {
    event.preventDefault();
    var mensagem = $('#mensagem').val();
    var chat = '<?php echo $chave; ?>';
    var user_name = '<?php echo $user_name; ?>';
    $.ajax({
      url: 'enviar_mensagem.php',
      type: 'POST',
      data: {
        chat: chat,
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
    var chat = '<?php echo $chave; ?>';
    num_mensagens = $('#mensagens2 .mensagem').length;
    $.ajax({
      url: 'atualizar_mensagens.php',
      type: 'POST',
      data: {
        chat: chat
      },
      success: function(response) {
        $('#mensagens2').html(response)
        num_novas_mensagens = $('#mensagens2 .mensagem').length - num_mensagens;
        if(num_novas_mensagens > 0){
            $('#mensagens').html(response)
            scroll();

            console.log("Atualizei.");
        }
        else{
            console.log("Nada novo.");
        }
      }
    });
  }
  setInterval(atualizarMensagens, 100);

});

function scroll(){
    const messagesDiv = document.querySelector('.mensagem');
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}
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
</style>

</html>