<?php
require 'vendor/autoload.php'; // Carrega a biblioteca mPDF
// Dados de conexão com o banco de dados
$host = 'localhost';
$dbname = 'biblioteca';
$username = 'root';
$password = 'ETEC_2024';

try{
$pdo = new PDO('mysql:host='.$hostname.'; dbname='.$dbname.'; charset=utf8', $username, $password);
$pdo->setAttribute (PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

// Consulta SQL para buscar informações dos livros
$query = "SELECT titulo, autor, ano_publicacao, resumo FROM livros";
$stmt = $pdo->prepare(query: $query);
$stmt->execute();
// Recupera os dados dos livros
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cria uma instância do mPDF
$mpdf = new \Mpdf\Mpdf();

// Configura o conteúdo do PDF
$html = '<h1>Biblioteca Lista de Livros</h1>';
$html .= '<table border="1" cellpadding="10" cellspacing="0" width="100%">';
$html .='<tr>
            <th>Titulo</th>
            <th>Autor</th>
            <th>Ano de Publicação</th>
            <th>Resumo</th>
        </tr>';
// Popula o HTML com os dados dos livros
foreach ($livros as $livro) {
    $html .= '<tr>';
    $html .= '<td>'. htmlspecialchars(string: $livro['titulo']). '</td>';
    $html .= '<td>'. htmlspecialchars(string: $livro['autor']). '</td>';
    $html .= '<td>'. htmlspecialchars(string: $livro['ano_publicacao']). '</td>';
    $html .= '<td>'. htmlspecialchars(string: $livro['resumo']) . '</td>';
    $html .= '</tr>';
}
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
} catch (\Mpdf\MpdfException $e) {
    echo "Erro ao gerar o PDF: " . $e->getMessage();
}

$html .= '</table>';

// Escreve o Conteúdo HTML no PDF
$mpdf->WriteHTML(html: $html);
// Gera o PDF e força o download
$mpdf->Output(name: 'lista_de_livros.pdf', dest: \Mpdf\Output\Destination:: DOWNLOAD);
?>