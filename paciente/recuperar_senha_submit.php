<?php

    /**
     * 
     * Importa o arquivo de
     * conexao com o banco de dados
     * 
     */
    include "../config/config.php";

    /**
     * 
     * Verifica se algum usuario 
     * foi enviado na requisiacao
     * para poder recuperar a senha
     * 
     */
    if ( isset( $_POST["idUsuario"] ) && !empty( $_POST["idUsuario"] ) ) {

        $usuario = $_POST["idUsuario"];

        $senha = md5( $_POST["senha"] );

        /**
         * 
         * Altera o toke de recuperacao
         * para apagado
         * 
         */
        $updateToken = "UPDATE tokens_recuperacao_senhas SET apagado = 1 WHERE id_usuario = :usuario";
        $updateToken = $pdo->prepare($updateToken);
        $updateToken->bindValue(":usuario", $usuario);
        if ( $updateToken->execute() == false ) {
            
            echo json_encode(
                [
                    "error" => "Falha ao atualizar token de recuperacação de senha."
                ]
            );

            exit;

        }

        /**
         * 
         * Atualiza a senha do usuario
         * 
         */
        $updatePassword = "UPDATE usuarios SET senha = :senha WHERE id_usuario = :usuario";
        $updatePassword = $pdo->prepare($updatePassword);
        $updatePassword->bindValue(":senha", $senha);
        $updatePassword->bindValue(":usuario", $usuario);
        if ( $updatePassword->execute() == false ) {
            
            echo json_encode(
                [
                    "error" => "Falha ao tentar atualizar senha."
                ]
            );

            exit;

        }


        /**
         * 
         * Mensagem de sucesso caso tenha 
         * tudo ocorrido certo 
         * 
         */
        echo json_encode(
            [
                "success" => "Senha atualizada com sucesso."
            ]
        );

        exit;

    /**
     * 
     * Retorna uma mensagem de erro 
     * caso nenhum usuario tenha sido
     * enviado
     * 
     */
    } else {
        
        echo json_encode(
            [
                "error" => "Falha ao tentar realizar ação."
            ]
        );

        exit;

    }