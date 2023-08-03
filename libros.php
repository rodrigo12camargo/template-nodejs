<?php
// 1. Establecer la conexión PDO
$host = 'localhost'; // Por ejemplo, 'localhost'
$dbname = 'prestamos';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}
//metodo get
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if(isset($_GET['id']))
    {
    $sql = $pdo->prepare("SELECT * FROM libros WHERE id =:id");
    $sql->bindValue(':id', $_GET['id']);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    header("HTTP/1.1 200 Correcto");
    echo json_encode($sql->fetchAll());
    exit();
    }
    
    else
    {
    $sql = $pdo->prepare("SELECT * FROM libros");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    header("HTTP/1.1 200 Correcto");
    echo json_encode($sql->fetchAll());
    exit();		
    }
}
//metodo post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    // Verificar que se recibieron los datos correctamente
    if (empty($data)) {
        http_response_code(400);
        echo "Error: No se recibieron datos JSON válidos.";
        exit;
    }

    // Procesar los datos y almacenarlos en la base de datos
    try {
        $stmt = $pdo->prepare('INSERT INTO libros (id, nombre, autor, editorial, resumen, estatus, existencia) 
                               VALUES (:id, :nombre, :autor, :editorial, :resumen, :estatus, :existencia)');

        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':autor', $data['autor']);
        $stmt->bindParam(':editorial', $data['editorial']);
        $stmt->bindParam(':resumen', $data['resumen']);
        $stmt->bindParam(':estatus', $data['estatus']);
        $stmt->bindParam(':existencia', $data['existencia']);
        $stmt->execute();

        // Obtener el ID del nuevo registro insertado
        $nuevo_id = $pdo->lastInsertId();

        // Enviar la respuesta exitosa al cliente
        echo "Enviado exitosamente. ID del nuevo registro: " . $nuevo_id;
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error en la inserción de datos: " . $e->getMessage();
    }
}
//metodo put
if ($_SERVER['REQUEST_METHOD'] === 'PUT' ) {


    // Procesar los datos y actualizarlos en la base de datos
    try {
        $stmt = $pdo->prepare('UPDATE libros SET nombre = :nombre, autor = :autor, editorial = :editorial, resumen = :resumen, estatus = :estatus, existencia = :existencia WHERE id = :id');

        $stmt->bindParam(':id', $_GET['id']);
        $stmt->bindParam(':nombre', $_GET['nombre']);
        $stmt->bindParam(':autor', $_GET['autor']);
        $stmt->bindParam(':editorial', $_GET['editorial']);
        $stmt->bindParam(':resumen', $_GET['resumen']);
        $stmt->bindParam(':estatus', $_GET['estatus']);
        $stmt->bindParam(':existencia', $_GET['existencia']);
        $stmt->execute();

        // Enviar la respuesta exitosa al cliente
        echo "Actualizado exitosamente.";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error en la actualización de datos: " . $e->getMessage();
    }
}
//metodo delete
if($_SERVER["REQUEST_METHOD"] == "DELETE")
{
    $id = $_GET['id'];
    $sql = $pdo->prepare("DELETE FROM libros WHERE id =:id");
    $sql->bindValue(':id', $id);
    $sql->execute();
    echo "Eliminado exitosamente.";
    exit();
}
?>