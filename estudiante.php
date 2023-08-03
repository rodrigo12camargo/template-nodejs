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
    $sql = $pdo->prepare("SELECT * FROM estudiante WHERE id =:id");
    $sql->bindValue(':id', $_GET['id']);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    header("HTTP/1.1 200 Correcto");
    echo json_encode($sql->fetchAll());
    exit();
    }
    
    else
    {
    $sql = $pdo->prepare("SELECT * FROM estudiante");
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
        $stmt = $pdo->prepare('INSERT INTO estudiante (nombre, a_paterno, a_materno, matricula) 
                               VALUES (:nombre, :a_paterno, :a_materno, :matricula)');

        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':a_paterno', $data['a_paterno']);
        $stmt->bindParam(':a_materno', $data['a_materno']);
        $stmt->bindParam(':matricula', $data['matricula']);
        $stmt->execute();

        http_response_code(200);
        echo "Estudiante creado exitosamente.";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
//metodo put
if($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    try{
        $stmt = $pdo->prepare("UPDATE estudiante SET nombre = :nombre, a_paterno = :a_paterno, a_materno = :a_materno, matricula = :matricula WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->bindParam(':nombre', $_GET['nombre']);
        $stmt->bindParam(':a_paterno', $_GET['a_paterno']);
        $stmt->bindParam(':a_materno', $_GET['a_materno']);
        $stmt->bindParam(':matricula', $_GET['matricula']);
        $stmt->execute();
        echo "Estudiante actualizado exitosamente.";
    }
    catch(PDOException $e)
    {
        die("Error: " . $e->getMessage());
    }
}
//metodo delete
if($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
    try
    {
        $stmt = $pdo->prepare("DELETE FROM estudiante WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        echo "Estudiante eliminado exitosamente.";
    }
    catch(PDOException $e)
    {
        die("Error: " . $e->getMessage());
    }
}
?>