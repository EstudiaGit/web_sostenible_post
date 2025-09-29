<?php

// --- INICIO DEL BLOQUE DE MANEJO DE CORS ---
// Permitir peticiones desde cualquier origen (ideal para desarrollo)
header("Access-Control-Allow-Origin: *");
// Permitir los métodos HTTP que nuestra API utiliza
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Permitir las cabeceras que el frontend podría enviar
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Si la petición es de tipo OPTIONS, es una comprobación previa (preflight).
// Respondemos con OK y terminamos el script. No necesitamos procesar nada más.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); // 200 OK
    exit();
}
// --- FIN DEL BLOQUE DE MANEJO DE CORS ---


// Definir el tipo de contenido de la respuesta como JSON
header('Content-Type: application/json');

include 'db.php';

define('API_KEY', 'my-secret-api-key');

$apiKey = null;
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $apiKey = trim($_SERVER['HTTP_AUTHORIZATION']);
}

if ($apiKey !== API_KEY) {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Clave de API no válida o no proporcionada']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

// Obtener el cuerpo de la petición (para POST, PUT, DELETE)
$input = json_decode(file_get_contents('php://input'), true);

match ($method) {
    'GET' => handleGet($pdo, $_GET),
    'POST' => createPost($pdo, $input),
    'PUT' => updatePost($pdo, $input),
    'DELETE' => deletePost($pdo, $input),
    default => http_response_code(405), // Method Not Allowed
};

function handleGet($pdo, $get) {
    if (isset($get['id'])) {
        $query = "SELECT * FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $get['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $limit = isset($get['limit']) ? (int)$get['limit'] : 10;
        $page = isset($get['page']) ? (int)$get['page'] : 1;
        $offset = ($page - 1) * $limit;

        $query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $query = "SELECT COUNT(*) as total FROM posts";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $result = $posts;
    }
    echo json_encode($result);
}

function createPost($pdo, $input) {
    if (!isset($input['title']) || empty($input['title'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'El título es requerido']);
        return;
    }
    if (!isset($input['status']) || empty($input['status'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'El estado es requerido']);
        return;
    }

    try {
        $query = "INSERT INTO posts (title, content, status) VALUES (:title, :content, :status)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':content', $input['content']);
        $stmt->bindParam(':status', $input['status']);
        
        if ($stmt->execute()) {
            $newPostId = $pdo->lastInsertId();
            $query = "SELECT * FROM posts WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $newPostId);
            $stmt->execute();
            $newPost = $stmt->fetch(PDO::FETCH_ASSOC);

            http_response_code(201); // 201 Created
            echo json_encode($newPost);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Fallo al crear el post']);
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['message' => 'Fallo al crear el post: ' . $e->getMessage()]);
    }
}

function updatePost($pdo, $input) {
    if (!isset($input['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'ID es requerido para actualizar']);
        return;
    }
    try {
        $query = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':content', $input['content']);
        $stmt->bindParam(':id', $input['id']);
        
        if ($stmt->execute()) {
            http_response_code(200); // OK
            echo json_encode(['message' => 'Post actualizado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Fallo al actualizar el post']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Fallo al actualizar el post: ' . $e->getMessage()]);
    }
}

function deletePost($pdo, $input) {
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'ID es requerido para eliminar']);
        return;
    }
    try {
        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $input['id']);
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Post eliminado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Fallo al eliminar el post']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Fallo al eliminar el post: ' . $e->getMessage()]);
    }
}
?>