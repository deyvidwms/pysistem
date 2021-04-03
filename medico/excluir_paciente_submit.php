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

        $usuarioExcluir = $_POST["usuarioExcluir"];

        /**
         * 
         * Atualiza o estado do usuario para apagado
         * 
         */
        $updateUser = "UPDATE usuarios SET apagado = 1 WHERE id_usuario = :usuario";
        $updateUser = $pdo->prepare($updateUser);
        $updateUser->bindValue(":usuario", $usuarioExcluir);
        if ( $updateUser->execute() == false ) {

            echo json_encode(
                [
                    "error" => "Falha ao tentar excluir paciente."
                ]
            );

            exit;

        }
        
        /**
         * 
         * Retorna uma mensagem de sucesso caso
         * de tudo certo
         * 
         */
        echo json_encode(
            [
                "success" => "Paciente deletado com sucesso."
            ]
        );

        exit;

    /**
     * 
     * Retorna uma mensagem de erro
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
