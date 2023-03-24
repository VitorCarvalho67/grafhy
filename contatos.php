<?php

include_once('config.php');

if(!isset($_COOKIE['user'])){
    header('location: login.php');
}
else{
    $user_name = $_COOKIE['user'];
}

$erro2 = "";

if(isset($_POST['submit']) && !empty($_POST['solicitacao'])){

    $userSelect = $_POST['solicitacao'];

   if($userSelect != $user_name){
    $verificar = "SELECT user_name FROM users WHERE user_name = '$userSelect'";

    $resultado = mysqli_query($conn, $verificar);

    $userNameReceiv = $_POST['solicitacao'];
    $userNameSend = $_COOKIE['user'];
    $data = date("Y-m-d H:i:s");

    $contato = mysqli_query($conn, "SELECT * FROM users WHERE user_name = '$user_name'");
    $contato2 = mysqli_query($conn, "SELECT * FROM users WHERE user_name = '$userNameReceiv'");

    if(mysqli_num_rows($contato) > 0 && mysqli_num_rows($contato2) > 0){
        $verificar = mysqli_query($conn, "SELECT * FROM solicitacoes WHERE user_name_recebe = '$userNameReceiv' AND user_name_envia = '$userNameSend'");

        if(mysqli_num_rows($verificar) > 0){
            $erro = "ERRO: Já existe uma solicitação de ou para ".$userNameReceiv;
        }
        else{
            $verificar = mysqli_query($conn, "SELECT * FROM contatos WHERE (user_name = '$userNameReceiv' AND user_name1 = '$userNameSend') OR (user_name1 = '$userNameReceiv' AND user_name = '$userNameSend')");
            if(mysqli_num_rows($verificar) > 0){
                $erro = "ERRO: ".$userNameReceiv." já é um contato.";
            }
            else{
                if(!empty($userNameReceiv) && !empty($userNameSend))
                mysqli_query($conn, "INSERT INTO solicitacoes VALUES (DEFAULT, '$userNameReceiv', '$userNameSend', '$data', '0')");
                $erro = "";
            }
        }
    }
    else{
        $erro = "ERRO: Usuário não encontrado";
    }
}

}

if(isset($_POST['aceitar'])){
    $id = $_POST['aceitar'];

    $contato1 = $user_name;

    $solicit = "SELECT user_name_envia FROM solicitacoes WHERE id = '$id'";
    $contato2_result = mysqli_query($conn, $solicit);
    $contato2 = mysqli_fetch_assoc($contato2_result)['user_name_envia'];

    $s = "SELECT * FROM contatos WHERE (user_name = '$user_name' AND user_name1 = '$contato2') OR(user_name = '$contato2' AND user_name1 = '$user_name')";
    $r = mysqli_query($conn, $s);

    if(mysqli_num_rows($r) == 0){
        mysqli_query($conn, "INSERT INTO contatos VALUES (DEFAULT, '$contato1', '$contato2')");
        mysqli_query($conn, "DELETE FROM solicitacoes WHERE id = '$id'");
        mysqli_query($conn, "INSERT INTO visualizadas(user,contato,visualizadas) values('$contato1', '$contato2', 0)");
        mysqli_query($conn, "INSERT INTO visualizadas(user,contato,visualizadas) values('$contato2', '$contato1', 0)");
    }  
}

if(isset($_POST['recusar'])){
    $id = $_POST['recusar'];
    mysqli_query($conn, "DELETE FROM solicitacoes WHERE id = '$id'");
}

if(isset($_POST['excluir'])){
    $id = $_POST['excluir'];
    mysqli_query($conn, "DELETE FROM solicitacoes WHERE id = '$id'");
}

if(isset($_POST['contato'])){
    $id = $_POST['contato'];
   
    header("location: privadas.php?id='$id'");
}

if(isset($_POST['criargrupo'])){
    if(!empty($_POST['grupo'])){
        $grupo = $_POST['grupo'];

        $s = "SELECT * FROM grupos WHERE grupo = '$grupo'";
        $r = mysqli_query($conn, $s);

        if(mysqli_num_rows($r) == 0){
            mysqli_query($conn, "INSERT INTO grupos VALUES (DEFAULT, '$grupo', '1', '1')");
            mysqli_query($conn, "INSERT INTO acessos_grupos VALUES (DEFAULT, '$user_name', '$grupo')");
        }
    }
    else{
        $erro2 = "Insira um nome!";
    }
}

if(isset($_POST['entrar'])){
    $id = $_POST['entrar'];

    $solicit = "SELECT grupo FROM convites WHERE id = '$id' ORDER BY grupo";
    $grupo_result = mysqli_query($conn, $solicit);
    $grupo = mysqli_fetch_assoc($grupo_result)['grupo'];

    $s = "SELECT * FROM acessos_grupos WHERE user_name = '$user_name' AND grupo = '$grupo' ORDER BY grupo";
    $r = mysqli_query($conn, $s);

    if(mysqli_num_rows($r) == 0){
        mysqli_query($conn, "INSERT INTO acessos_grupos VALUES (DEFAULT, '$user_name', '$grupo')");
        mysqli_query($conn, "DELETE FROM convites WHERE id = '$id'");
    }  
}

if(isset($_POST['recusargrupo'])){
    $id = $_POST['recusargrupo'];
    mysqli_query($conn, "DELETE FROM convites WHERE id = '$id'");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafhy</title>
    <link rel="stylesheet" href="style/contato.css">
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

    <form action="" method="POST" class="setChave" class="d">
        <div class="conteudo">
            <label>Procurar: </label>
            <input type="text" id="solicitacao" name="solicitacao" placeholder="UserName" autocomplete="off">
        </div>
        <div class="erro"><?php if(isset($erro)){echo $erro;}?></div>
        <input type="submit" id="submit" name="submit" value="Enviar solicitação">
    </form>
    
   <main>

        <div class="contatos" id="contatos">
                
            </div>
            
            <div class="contato" id="c3"><a>Novo Grupo</a></div>


            <div id="newGroup">
                <div id="a"></div>
                <form action="" method="POST" id="criar" class="setChave" name="criar">
                    <div class="conteudo">
                        <label>Criar grupo: </label>
                        <input type="text" id="grupo" name="grupo" placeholder="Nome" autocomplete="off">
                    </div>
                    <div class="erro"><?php if(isset($erro2)){echo $erro2;}?></div>
                    <input type="submit" id="submit" name="criargrupo" value="Criar Grupo">
                </form>
            </div>
   </main>

   <input type="hidden" id="uptade1" class="uptade1">

   <?php echo $footer?>
   
</body>
<script src="script/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('c3').onclick = () => {
        newGroup.style.display = "flex";
    }

    document.getElementById('a').onclick = () => {
        newGroup.style.display = "none";
    }

    function atualizarcontatos(){
        var user = '<?php echo $user_name;?>';

        $.ajax({
            url: 'atualizar_contatos.php',
            type: 'post',
            data: {
                user: user
            },
            success: function(response){
                
                
                            
                if (!($('#contatos').html() == response)) {
                    $('#contatos').html(response);
                }
                            
            }
        });
    }

    setInterval(atualizarcontatos, 1000);

</script>

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