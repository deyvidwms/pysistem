<?php
    /**
     * 
     * Inicia a sessao para 
     * encerrar e redirecionar 
     * para a pagina de login 
     * 
     */
    session_start();
    session_destroy();
    header("Location: login.php");
?>