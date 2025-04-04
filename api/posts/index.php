<?php

    $env = parse_ini_file('../../.env');
    define('DBNAME', $env['DBNAME']);
    define('DBHOST', $env['DBHOST']);
    define('DBUSER', $env['DBUSER']);
    define('DBPASS', $env['DBPASS']);

    $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$uriArray = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($uriArray[0] === 'api' && $uriArray[1] === 'posts') {
    header('Content-Type: application/json');

    if ($requestMethod === 'GET') {
        $query = "SELECT * FROM posts";
        $result = $conn->query($query);
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($posts);
        exit();
    }

    if ($requestMethod === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['title'], $data['content'])) {
            echo json_encode(["error" => "Invalid request: title and content required"]);
            exit();
        }

        $query = "INSERT INTO posts (title, content) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $data['title'], $data['content']);
        $success = $stmt->execute();

        echo json_encode(["success" => $success, "data" => ["title" => $data['title'], "content" => $data['content']]]);
        exit();
    }

    if ($requestMethod === 'DELETE' && isset($uriArray[2])) {
        $id = intval($uriArray[2]);
        $query = "DELETE FROM posts WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        echo json_encode(["success" => $success, "deleted_id" => $id]);
        exit();
    }
}

?>