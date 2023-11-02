<!DOCTYPE html>
<html>
<head>
    <title>Formulário de Envio de Arquivo PDF - Novo</title>
</head>
<body>

<h2>Envie um arquivo PDF</h2>

<form action="recebeArquivo.php" method="post" enctype="multipart/form-data">
    <label for="files">Escolha um arquivo PDF:</label>
    <input type="file" name="files" id="files" accept=".pdf">
    <br>
    <input type="submit" value="Enviar PDF">
</form>

</body>
</html>
