<?php
    include "../config/config.php";

    /**
     * 
     * Tipo 1 - Cadastro de sentimento
     * Tipo 2 - Edicao de sentimento
     * 
     */
    if ( isset( $_GET["tipo"] ) && !empty( $_GET["tipo"] ) && $_GET["tipo"] == 1 ) {

        /**
         * 
         * Verifica se foi enviado
         * o id do paciente
         * 
         */
        if ( isset( $_POST["usuario"] ) && !empty( $_POST["usuario"] ) ) {

            $usuario = $_POST["usuario"];

            $categoria = $_POST["categoria"];        

            $nota = $_POST["nota"];        

            $feedback = utf8_decode( $_POST["feedback"] );
                
            /**
             * 
             * Insere o sentimento no banco de dados
             * 
             */
            $insertSentimento = "INSERT INTO sentimentos ( id_usuario, id_categoria_dos_sentimentos, nota_sentimento, descricao ) 
                                    VALUES ( :usuario, :categoria, :nota, :descricao)";
            $insertSentimento = $pdo->prepare($insertSentimento);
            $insertSentimento->bindValue(":usuario", $usuario);
            $insertSentimento->bindValue(":categoria", $categoria);
            $insertSentimento->bindValue(":nota", $nota);
            $insertSentimento->bindValue(":descricao", $feedback);
            if ( $insertSentimento->execute() == false ) {
                
                echo json_encode(
                    [
                        "error" => "Falha ao tentar cadastrar sentimento."
                    ]
                );

                exit;

            }

            /**
             * 
             * Retorna uma mensagem de sucesso
             * 
             */
            echo json_encode(
                [
                    "success" => "Sentimento cadastrado com sucesso."
                ]
            );

            exit;

        /**
         * 
         * Retorna uma mensagem de erro
         * caso nao tenha enviado o 
         * id do paciente
         * 
         */
        } else {
         
            echo json_encode(
                [
                    "error" => "Falha ao tentar cadastrar sentimento."
                ]
            );
    
            exit;
    
        }

    } else if ( isset( $_GET["tipo"] ) && !empty( $_GET["tipo"] ) && $_GET["tipo"] == 2 ) {

        /**
         * 
         * Verifica se o id do paciente foi
         * enviado na requisicao
         * 
         */
        if ( isset( $_POST["idUsuario"] ) && !empty( $_POST["idUsuario"] ) ) {

            $usuario = $_POST["idUsuario"];

            $sentimento = $_POST["sentimento"];

            $categoria = $_POST["categoria"];

            $nota = $_POST["nota"];
            
            $feedback = utf8_decode( $_POST["feedback"] );
                    
            /**
             * 
             * Atualiza o sentimento no banco de dados
             * 
             */
            $updateUser = "UPDATE sentimentos SET id_categoria_dos_sentimentos = :categoria, nota_sentimento = :nota, descricao = :descricao WHERE id_usuario = :usuario AND id_sentimento = :sentimento";
            $updateUser = $pdo->prepare($updateUser);
            $updateUser->bindValue(":categoria", $categoria);
            $updateUser->bindValue(":nota", $nota);
            $updateUser->bindValue(":descricao", $feedback);
            $updateUser->bindValue(":usuario", $usuario);
            $updateUser->bindValue(":sentimento", $sentimento);
            if ( $updateUser->execute() == false ) {

                echo json_encode(
                    [
                        "error" => "Falha ao atualizar dados do sentimento."
                    ]
                );

                exit;

            } 

            /**
             * 
             * Retorna uma mensagem de sucesso
             * 
             */
            echo json_encode(
                [
                    "success" => "Sentimento atualizado com sucesso."
                ]
            );

            exit;

        /**
         * 
         * Mensagem de erro caso nao tenha
         * enviado o id do paciente
         * 
         */
        } else {

            echo json_encode(
                [
                    "error" => "Falha ao tentar editar sentimento."
                ]
            );
    
            exit;

        }

    /**
     * 
     * Redireciona para a pagina inicial
     * caso nao tenha enviado o tipo da
     * requisicao
     * 
     */
    } else {

        header("Location: index.php");

    }