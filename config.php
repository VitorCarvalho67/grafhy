<?php

session_start();

$bd_host = 'localhost';
$bd_user = 'root';
$bd_pass = '123';
$db_port = '5858';
$bd_name = 'grafhy';

$conn = new mysqli($bd_host, $bd_user, $bd_pass, $bd_name,$db_port);

if(isset($_COOKIE['user'])){
    $name = $_COOKIE['user'];
}

else{
    $name = "Login";
}

$nav= "<nav>
<div class=\"logo\">Grafy</div>
<div class=\"content\">
    <a href=\"login.php\">$name</a>
    <a href=\"index.php\">Chats</a>
    <a href=\"contatos.php\">Contatos</a>
    <button id=\"btn1\">perfil</button>
</div>

</nav>";

$footer = "  <footer>
<p id=\"year\"></p>
<script>
    var data = new Date();
    year.innerText = \"Copyright © \" + data.getFullYear() + \" - Grafhy\";
</script>
</footer>";

//phpinfo();

?>
