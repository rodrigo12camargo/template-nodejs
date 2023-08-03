<?php
// Definir el punto de entrada para crear la base de datos y las tablas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    createDatabaseAndTables();
}

// Función para crear la base de datos y las tablas
function createDatabaseAndTables()
{
    // Datos de conexión a la base de datos
    $host = 'localhost'; // Por ejemplo, 'localhost'
    $dbname = 'prestamos';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Crear la base de datos prestamos
        $pdo->exec('CREATE DATABASE IF NOT EXISTS prestamos');
        $pdo->exec('USE prestamos');

        // Crear la tabla "libros"
        $pdo->exec('CREATE TABLE IF NOT EXISTS libros (
            id INT PRIMARY KEY,
            nombre VARCHAR(250),
            autor VARCHAR(250),
            editorial VARCHAR(250),
            resumen VARCHAR(250),
            estatus VARCHAR(250),
            existencia VARCHAR(250)
        )');

        // Crear la tabla "estudiante"
        $pdo->exec('CREATE TABLE IF NOT EXISTS estudiante (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(250),
            a_paterno VARCHAR(250),
            a_materno VARCHAR(250),
            matricula VARCHAR(250)
        )');

        // Crear la tabla "mochila"
        $pdo->exec('CREATE TABLE IF NOT EXISTS mochila (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fecha_prestamo VARCHAR(250),
            fecha_entrega VARCHAR(250),
            id_estudiante INT,
            FOREIGN KEY (id_estudiante) REFERENCES estudiante(id)
        )');

        // Crear la tabla intermedia "mochila_libros" para la relación muchos a muchos
        $pdo->exec('CREATE TABLE IF NOT EXISTS mochila_libros (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_mochila INT,
            id_libro INT,
            FOREIGN KEY (id_mochila) REFERENCES mochila(id),
            FOREIGN KEY (id_libro) REFERENCES libros(id)
        )');

        echo "Base de datos y tablas creadas exitosamente.";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
