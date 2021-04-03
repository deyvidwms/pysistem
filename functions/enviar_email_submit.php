<?php

    /**
     * 
     * Importa o arquivo de conexao com o banco
     * 
     */
    include "../config/config.php";

    /**
     * 
     * Importa o arquivo de funcoes
     * 
     */
    include "./functions.php";


    /**
     * 
     * Verifica se foi enviado um
     * email na requisicao para poder
     * dar continuidade no processo de 
     * envio de emails.
     * 
     */
    if ( isset( $_POST["email"] ) && !empty( $_POST["email"] ) ) {

        $email = $_POST["email"];

        $bool = $_POST["bool"];

        /**
         * 
         * Verficia se existe algum usuario com esse email
         * 
         */
        $selectEmail = "SELECT * FROM usuarios WHERE email = :email";
        $selectEmail = $pdo->prepare($selectEmail);
        $selectEmail->bindValue(":email", $email);
        if( $selectEmail->execute() == false ) {

            echo json_encode(
                [
                    "error" => "Falha ao buscar informações do usuário."
                ]
            );

            exit;

        } else if ( $selectEmail->rowCount() == 0 ) {

            echo json_encode(
                [
                    "error" => "Esse email não corresponde ao de nenhum usuário."
                ]
            );

            exit;

        } else if ( $selectEmail->rowCount() > 0 ) {

            $dadosUsuario = $selectEmail->fetch();

            /**
             * 
             * Gera um novo token
             * 
             */
            $token = gerar_token(32, true, true, true, false);


            /**
             * 
             * Atualiza todos os tokens de recuperacao
             * desse usuario para apagado
             * 
             */
            $updateTokens = "UPDATE tokens_recuperacao_senhas SET apagado = 1 WHERE id_usuario = :usuario";
            $updateTokens = $pdo->prepare($updateTokens);
            $updateTokens->bindValue(":usuario", $dadosUsuario["id_usuario"]);
            if ( $updateTokens->execute() == false ) {

                echo json_encode(
                    [
                        "error" => "Falha ao tentar gerar token de recuperação."
                    ]
                );

                exit;

            }

            /**
             * 
             * Insere o novo token de 
             * recuperacao para o usuario
             * 
             */
            $insertToken = "INSERT INTO tokens_recuperacao_senhas (id_usuario, token) VALUES (:usuario, :token)";
            $insertToken = $pdo->prepare($insertToken);
            $insertToken->bindValue(":usuario", $dadosUsuario["id_usuario"] );
            $insertToken->bindValue(":token", $token);
            if ( $insertToken->execute() == false ) {

                echo json_encode(
                    [
                        "error" => "Falha ao tentar gerar um novo token de recuperação."
                    ]
                );

                exit;

            }

            /**
             * 
             * Envia uma mensagem para o 
             * email do usuario
             * 
             * se retornar o retorno for:
             * true - retorna a pagina index.php
             * false - retorna uma mensagem de erro
             * 
             */
            if ( sendRecoveryEmail($email, $token, $bool) == true ) {

                echo json_encode(
                    [
                        "link" => "index.php"
                    ]
                );

                exit;

            } else {

                echo json_encode(
                    [
                        "error" => "Erro ao tentar enviar email."
                    ]
                );

                exit;

            }

        }


    /**
     * 
     * Caso nao haja email
     * eh retornado uma mensagem
     * de erro.
     * 
     */
    } else {
        
        echo json_encode(
            [
                "error" => "Falha ao tentar enviar email."
            ]
        );
        
        exit;

    }