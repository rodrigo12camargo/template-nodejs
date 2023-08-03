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
//get 
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if(isset($_GET['id']))
    {
    $sql = $pdo->prepare("SELECT * FROM mochila WHERE id =:id");
    $sql->bindValue(':id', $_GET['id']);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    header("HTTP/1.1 200 Correcto");
    echo json_encode($sql->fetchAll());
    exit();
    }
    
    else
    {
    $sql = $pdo->prepare("SELECT * FROM mochila");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    header("HTTP/1.1 200 Correcto");
    echo json_encode($sql->fetchAll());
    exit();		
    }
}
//post
if($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['CONTENT_TYPE'] === 'application/json')
{
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    //verificar que se recibieron los datos correctamente
    if(empty($data))
    {
        http_response_code(400);
        echo "Error: No se recibieron datos JSON válidos.";
        exit;
    }
    //procesar los datos y almacenarlos en la base de datos
    try
    {
        $stmt = $pdo->prepare('INSERT INTO mochila (libro1, libro2, libro3, fecha_prestamo, fecha_entrega, id_estudiantes) 
                               VALUES (:libro1, :libro2, :libro3, :fecha_prestamo, :fecha_entrega, :id_estudiantes)');
        $stmt->bindParam(':libro1', $data['libro1']);
        $stmt->bindParam(':libro2', $data['libro2']);
        $stmt->bindParam(':libro3', $data['libro3']);
        $stmt->bindParam(':fecha_prestamo', $data['fecha_prestamo']);
        $stmt->bindParam(':fecha_entrega', $data['fecha_entrega']);
        $stmt->bindParam(':id_estudiantes', $data['id_estudiantes']);
        $stmt->execute();
        echo "Datos insertados correctamente";
    }
    catch(PDOException $e)
    {
        die("Error: " . $e->getMessage());
    }
}
//put
if($_SERVER['REQUEST_METHOD'] === 'PUT')
{
    //procesar los datos y almacenarlos en la base de datos
    try
    {
        $stmt = $pdo->prepare('UPDATE mochila SET libro1=:libro1, libro2=:libro2, libro3=:libro3, fecha_prestamo=:fecha_prestamo, fecha_entrega=:fecha_entrega, id_estudiantes=:id_estudiantes WHERE id=:id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->bindParam(':libro1', $_GET['libro1']);
        $stmt->bindParam(':libro2', $_GET['libro2']);
        $stmt->bindParam(':libro3', $_GET['libro3']);
        $stmt->bindParam(':fecha_prestamo', $_GET['fecha_prestamo']);
        $stmt->bindParam(':fecha_entrega', $_GET['fecha_entrega']);
        $stmt->bindParam(':id_estudiantes', $_GET['id_estudiantes']);
        $stmt->execute();
        echo "Datos actualizados correctamente";
    }
    catch(PDOException $e)
    {
        die("Error: " . $e->getMessage());
    }
}
//delete
if($_SERVER['REQUEST_METHOD'] === 'DELETE')
{
    //procesar los datos y almacenarlos en la base de datos
    try
    {
        $stmt = $pdo->prepare('DELETE FROM mochila WHERE id=:id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        echo "Datos eliminados correctamente";
    }
    catch(PDOException $e)
    {
        die("Error: " . $e->getMessage());
    }
}

?>