
<?php
    require_once './vendor/autoload.php';
    class Postagens extends AR\ABS\ActiveRecord{
        
    }
    
    $cliente = Postagens::find(20000);
    echo '<pre>';
    var_dump($cliente);
    echo '</pre>';
   
    
?>
