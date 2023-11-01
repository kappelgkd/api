<?php 

function enviarArquivo($arquivo,$nomeArquivo)
{


    // URL para a qual você deseja enviar a requisição cURL
    // $url = '192.168.0.13:8000/recebeArquivo.php';
    // $url = 'localhost:8000/recebeArquivo.php';
    
    // $arquivo = "C:/Users/dinam/Downloads/1679073826793.pdf";
    // $nomeArquivo = "1679073826793.pdf";
    
    $url = '192.168.0.13:8000/recebeArquivo.php';
    $nomeArq = $nomeArquivo;
    
    $post = array(
        'files[0]' => new CURLFile($arquivo, "application/pdf", $nomeArq)
    );

    $authorization = "Authorization: Bearer 1234";
    // Inicializa a sessão cURL
    $ch = curl_init();

    // Configura as opções da requisição cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15', 'Referer: https://assim.com.br', 'Content-Type: multipart/form-data', $authorization));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true); // enable posting
    curl_setopt($ch, CURLOPT_POST, 1); // Define o método HTTP como POST (pode ser GET, PUT, etc.)
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna a resposta como uma string

    // Executa a requisição cURL e armazena a resposta na variável $response
    $response = curl_exec($ch);

    // Verifica por erros na requisição cURL
    if (curl_errno($ch)) {
        echo 'Erro na requisição cURL: ' . curl_error($ch);
    }

    // Fecha a sessão cURL
    curl_close($ch);
    echo $response;
}

function gravarArquivo($nomeArquivo, $caminhoArquivo, $con){
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try{

    $arquivo = $nomeArquivo;
    $caminho_arquivo = $caminhoArquivo;
    $extensao = explode('.',$arquivo)[1];
    $status = 1;

    $stmt = $con->prepare("INSERT INTO tb_arquivos (arquivo, caminho_arquivo, extensao, status) VALUES(:nomeArquivo, :caminhoArquivo,:extensao, :status)");

    

    $stmt->bindValue(':nomeArquivo', $arquivo);
    $stmt->bindValue(':caminhoArquivo', $caminho_arquivo);
    $stmt->bindValue(':extensao', $extensao);
    $stmt->bindValue(':status', $status);
    $stmt->execute();
   
    return 200;
}

    catch (PDOException $e) {
        // Em caso de erro, exibe a mensagem de erro
        echo "Erro: " . $e->getMessage();
    }

}