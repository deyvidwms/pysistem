<?php

    /*
     * Nome: config.php
     *
     * Pagina que faz conexao com o banco de dados.
     *
     * @author Deyvid
     *
     * */
    $dsn = "mysql:dbname=deyviddev_pysistem;host=localhost";
    $dbuser = "deyviddev_pysistem";
    $dbpass = "Psystem@2021";

    try {

        $pdo = new PDO($dsn, $dbuser, $dbpass);

    } catch (PDOException $e) {
        echo "Erro: ".$e->getMessage();
    }

    /*
     *
     * Definindo horario do banco de dados
     *
     * */
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

    date_default_timezone_set('America/Sao_Paulo');

?>
