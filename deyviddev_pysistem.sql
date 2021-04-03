CREATE TABLE `categorias_dos_sentimentos` (
 `id_categoria_dos_sentimentos` int(11) NOT NULL AUTO_INCREMENT,
 `nome` varchar(100) NOT NULL,
 `apagado` tinyint(4) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id_categoria_dos_sentimentos`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8

CREATE TABLE `pacientes_dos_medicos` (
 `id_paciente_do_medico` int(11) NOT NULL AUTO_INCREMENT,
 `id_usuario_medico` int(11) NOT NULL,
 `id_usuario_paciente` int(11) NOT NULL,
 `apagado` tinyint(4) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id_paciente_do_medico`,`id_usuario_medico`,`id_usuario_paciente`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8

CREATE TABLE `sentimentos` (
 `id_sentimento` int(11) NOT NULL AUTO_INCREMENT,
 `id_usuario` int(11) NOT NULL,
 `id_categoria_dos_sentimentos` int(11) NOT NULL,
 `nota_sentimento` int(11) NOT NULL,
 `descricao` text CHARACTER SET utf8,
 `criado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `atualizado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `apagado` tinyint(4) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id_sentimento`,`id_usuario`,`id_categoria_dos_sentimentos`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8

CREATE TABLE `tokens_recuperacao_senhas` (
 `id_token_recupercao_senha` int(11) NOT NULL AUTO_INCREMENT,
 `id_usuario` int(11) NOT NULL,
 `token` varchar(100) NOT NULL,
 `criado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `atualizado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `apagado` tinyint(4) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id_token_recupercao_senha`,`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8

CREATE TABLE `usuarios` (
 `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
 `nome` varchar(250) CHARACTER SET utf8 NOT NULL,
 `usuario` varchar(100) CHARACTER SET utf8 NOT NULL,
 `email` varchar(250) CHARACTER SET utf8 NOT NULL,
 `cpf` varchar(14) CHARACTER SET utf8 NOT NULL,
 `telefone` varchar(15) CHARACTER SET utf8 NOT NULL,
 `senha` varchar(32) CHARACTER SET utf8 NOT NULL,
 `tipo` tinyint(4) NOT NULL,
 `apagado` tinyint(4) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8