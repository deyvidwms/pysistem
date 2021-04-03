<?php

    /**
	 * 
	 * Importacao do arquivo de 
	 * conexao com o banco de dados
	 * 
	 */
    include "../config/config.php";

    /**
     * 
     * Verifica se o id do 
     * usuario foi enviado
     * 
     */
    if ( isset( $_POST["usuario"] ) && !empty( $_POST["usuario"] ) ) {

        $usuario = $_POST["usuario"];
        $sentimento = $_POST["sentimento"];

        /**
         * 
         * Atualiza o estado do sentimento para apagado
         * 
         */
        $updateSentimento = "UPDATE sentimentos SET apagado = 1 WHERE id_sentimento = :sentimento AND id_usuario = :usuario";
        $updateSentimento = $pdo->prepare($updateSentimento);
        $updateSentimento->bindValue(":sentimento", $sentimento);
        $updateSentimento->bindValue(":usuario", $usuario);
        if ( $updateSentimento->execute() == false ) {

            echo json_encode(
                [
                    "error" => "Falha ao tentar excluir sentimento."
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
                "success" => "Sentimento deletado com sucesso."
            ]
        );

        exit;

    /**
     * 
     * Retorna uma mensagem de erro caso o
     * id do usuario nao tenha sido enviado
     * 
     */
    } else {

        echo json_encode(
            [
                "error" => "Você não tem permissão para realizar esta ação."
            ]
        );

        exit;

    }
