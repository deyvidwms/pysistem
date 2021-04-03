<?php

    /*
     *
     * Iniciando sessao para saber se o usuario
     * esta logado ou nao
     *
     * */
    session_start();

    /*
     *
     * Importacao do arquivo de conexao
     * com o banco de dados
     *
     * */
    include "../config/config.php";

    /**
     * 
     * Verifica se foi enviado o login (email ou usuario)
     * 
     */
    if( isset($_POST["usuario"]) && isset($_POST["senha"]) ) {

        $usuario = $_POST["usuario"];

        $senha = md5($_POST["senha"]);

        // vai verificar se o valor digita para usuario foi um email ou username
        $pos = strpos($usuario, '@');

        // caso nome de usuario
        if($pos === false) {

            /**
             * 
             * Verifica o medico a partir do nome
             * de usuario
             * 
             */
            $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND senha = :senha AND tipo = 1 AND apagado = 0";
            $sql = $pdo->prepare($sql);
            $sql->bindValue(":usuario", $usuario);
            $sql->bindValue(":senha", $senha);
            $sql->execute();

            if($sql->rowCount() > 0) {

                $resultado = $sql->fetchAll();
                
                foreach ( $resultado as $item ) {
                
                    if ( ( $usuario == $item['usuario'] ) && ( $senha == $item['senha'] ) ) {
                        
                        $_SESSION['id'] = $item['id_usuario'];
                        
                        $_SESSION['usuario'] = $item['usuario'];
                        
                        echo "index.php";
                        
                        exit;

                    }
                
                }

            }

        // caso email
        }else {

            /**
             * 
             * Verifica o medico a partir do email
             * de usuario
             * 
             */
            $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha AND tipo = 1 AND apagado = 0";
            $sql = $pdo->prepare($sql);
            $sql->bindValue(":email", $usuario);
            $sql->bindValue(":senha", $senha);
            $sql->execute();

            if ( $sql->rowCount() > 0 ) {
                
                $resultado = $sql->fetchAll();

                foreach ( $resultado as $item ) {
                
                    if( ( $usuario == $item['email'] ) && ( $senha == $item['senha'] ) ) {
                
                        $_SESSION['id'] = $item['id_usuario'];
                
                        $_SESSION['usuario'] = $item['usuario'];
                
                        echo "index.php";
                
                        exit;
                
                    }
                
                }
            
            }

        }

    }


?>

