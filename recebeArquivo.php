<?php 
// echo "<pre>";
// // print_r($_FILES);
// print_r($_SERVER);
// exit;
include("lib/conexao.php");
include("lib/funcoes.php");
// echo "<pre>";
// print_r($_FILES);
// exit;

$method = $_SERVER["REQUEST_METHOD"];
try {

    if ($method != 'POST') {
        throw new Exception("Método não suportado", 301);
    }

    
    // if (count($_FILES["files"]["name"]) == 0) {
    //     throw new Exception("Necessário enviar arquivos",303);
    // }
}

catch (Exception $e) {
    $response['msg'] =  $e->getMessage()."|".$e->getLine()."|".$e->getFile();
    $response['status'] = $e->getCode();
}


try{
    
    if (isset($_FILES["files"])) {
        
        $arquivo = $_FILES["files"];
        $erro = 0;
        // Verifica se não ocorreu erro durante o envio
        
        if ($erro === 0) {
            // Diretório de destino para salvar o arquivo
            $diretorio_destino = "uploads/pdf/"; // Substitua pela pasta desejada
            
            if(!is_dir($diretorio_destino)){
               if(!mkdir($diretorio_destino,0777, true)){
                $response["status"] = 300;
                $response["mensagem"] ="pasta nao pode ser criada";
               }
            }

            // Move o arquivo para o diretório de destino
            if (move_uploaded_file($arquivo["tmp_name"][0], $diretorio_destino . $arquivo["name"][0])) {
                
                if(gravarArquivo($arquivo["name"][0],$diretorio_destino,$con)){
                    $response["status"] = 200;
                    $response["mensagem"] ="Arquivo enviado com sucesso!";
                }
                // caso nao seja gravado, lançar erro e apagar arquivos do diretório
                
            } else {
                $response["status"] = 400;
                $response["mensagem"] ="Erro ao mover o arquivo para o diretório de destino.";
                // echo "Erro ao mover o arquivo para o diretório de destino.";
            }
        } else {
            $response["status"] = 400;
            $response["mensagem"] = "Erro no envio do arquivo: " . $arquivo["error"];
            // echo "Erro no envio do arquivo: " . $arquivo["error"];
        }
    } else {
        $response["status"] = 400;
        $response["mensagem"] = "Nenhum arquivo foi enviado.";
        // echo "Nenhum arquivo foi enviado."
    }
}

catch (Exception $e) {
    $response['status'] = $e->getCode();
    $response['mensagem'] =  $e->getMessage()."|".$e->getLine()."|".$e->getFile();
    
}

echo json_encode($response);
