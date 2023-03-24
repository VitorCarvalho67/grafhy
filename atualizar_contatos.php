<?php 
include_once('config.php');

$user_name = $_POST['user'];

?>

<?php 
        $solicit = "SELECT * FROM contatos WHERE user_name = '$user_name' OR user_name1 = '$user_name'";
        $result = mysqli_query($conn, $solicit);
        
        while($var = mysqli_fetch_assoc($result)){?>
            <?php 
            
            if($var['user_name'] == $user_name){
                $contato = $var['user_name1'];

                $teste = str_replace('tabela_grupo', '', $contato);

                if(!($teste == $contato)){
                    $contato = $teste;
                }
            }
            else{
                $contato = $var['user_name'];
            }
            $ex = mysqli_query($conn, "SELECT * FROM privado where (user_name='$user_name' and contato='$contato') or  (user_name='$contato' and contato='$user_name')");
            $qta1 = mysqli_num_rows($ex);

            $visu = mysqli_query($conn, "SELECT visualizadas from visualizadas where user='$user_name' and contato='$contato'");
            $visuArray = mysqli_fetch_assoc($visu);
            $visuValue = 0;

            if ($visuArray != null) {
                $visuValue = $visuArray['visualizadas'];
            }

            $qta = $qta1 - $visuValue;

            if($qta > 0){
                echo "<div class=\"contato\"><a href=\"privadas.php?id='$contato'\"> $contato <div class=\"qta\">$qta</div></a></div>";
            } else {
                echo "<div class=\"contato\"><a href=\"privadas.php?id='$contato'\">$contato</a></div>";
            }
        ?></p>
            
    <?php } ?>

    <?php 
        $solicit = "SELECT * FROM acessos_grupos WHERE user_name = '$user_name'";
        $result = mysqli_query($conn, $solicit);
        
        while($var = mysqli_fetch_assoc($result)){
            
            $grupo = $var['grupo'];

            $grupo = str_replace('tabela_grupo', '', $grupo);

            echo "<div class=\"contato\"><a href=\"grupo.php?id='" . $grupo . "'\"><p>" . $grupo . "</p>
            <svg fill=\"none\" height=\"24\" stroke-width=\"1.5\" viewBox=\"0 0 24 24\" width=\"24\">
                <path d=\"M1 20V19C1 15.134 4.13401 12 8 12V12C11.866 12 15 15.134 15 19V20\" stroke=\"currentColor\" stroke-linecap=\"round\"/>
                <path d=\"M13 14V14C13 11.2386 15.2386 9 18 9V9C20.7614 9 23 11.2386 23 14V14.5\" stroke=\"currentColor\" stroke-linecap=\"round\"/>
                <path d=\"M8 12C10.2091 12 12 10.2091 12 8C12 5.79086 10.2091 4 8 4C5.79086 4 4 5.79086 4 8C4 10.2091 5.79086 12 8 12Z\" stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>
                <path d=\"M18 9C19.6569 9 21 7.65685 21 6C21 4.34315 19.6569 3 18 3C16.3431 3 15 4.34315 15 6C15 7.65685 16.3431 9 18 9Z\" stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/>
            </svg></a></div></p>";
        } ?>

    <?php 
    $solicit = "SELECT * FROM solicitacoes WHERE user_name_recebe = '$user_name'";
    $result = mysqli_query($conn, $solicit);

    if(mysqli_num_rows($result) > 0){

    while($var = mysqli_fetch_assoc($result)){ 
        if($var['situacao'] == 0){?>
        <form name="form2" method="POST" class="contato2">
            <div>
                <p><?php echo "Pedido de: " . $var['user_name_envia'];?></p>
                <p class="data"><?php echo $var['data_hora'];?></p>
            </div>
            <div class="acr">
                <button type="submit" name="aceitar" value="<?php echo $var['id'];?>">Aceitar</button>
                <button type="submit" name="recusar" value="<?php echo $var['id'];?>">Recusar</button>
            </div>
        </form>
    <?php }}} ?>

    <?php 
        $solicit = "SELECT * FROM solicitacoes WHERE user_name_envia = '$user_name'";
        $result = mysqli_query($conn, $solicit);
        if(mysqli_num_rows($result) > 0){
        while($var = mysqli_fetch_assoc($result)){
        if($var['situacao'] == 0){?>
        <form name="form3" method="POST" class="contato2">
            <div>
                <p><?php echo "Para: " . $var['user_name_recebe'];?></p>
                <p class="data"><?php echo $var['data_hora'];?></p>
            </div>
            <button type="submit" name="excluir" value="<?php echo $var['id'];?>">Apagar</button>
        </form>
    <?php } } }?>
        
    <?php 
    $solicit = "SELECT * FROM convites WHERE user_name_recebe = '$user_name'";
    $result = mysqli_query($conn, $solicit);

    if(mysqli_num_rows($result) > 0){

    while($var = mysqli_fetch_assoc($result)){ 
        
        $grupo = $var['grupo'];?>

        <form name="form7" method="POST" class="contato2">
            <div>
                <p><?php echo "Convite para " . $grupo;?></p>
                <p class="data"><?php echo $var['data_hora'];?></p>
            </div>
            <div class="acr">
                <button type="submit" name="entrar" value="<?php echo $var['id'];?>">Entrar</button>
                <button type="submit" name="recusargrupo" value="<?php echo $var['id'];?>">Recusar</button>
            </div>
        </form>
    <?php }} ?>