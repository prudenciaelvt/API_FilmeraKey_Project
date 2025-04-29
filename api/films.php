<?php
header("Content-Type: application/json; charset=UTF-8");

require_once "../config_db/db_koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM tbl_films WHERE id = $id";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                echo json_encode($result->fetch_assoc());
                http_response_code(200);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Film tidak ditemukan"]);
            }
        } elseif (isset($_GET['search'])) {
            $search = '%' . $conn->real_escape_string($_GET['search']) . '%';
            $sql = "SELECT * FROM tbl_films WHERE title LIKE '$search'";
            $result = $conn->query($sql);
    
            $films = [];
            while ($row = $result->fetch_assoc()) {
                $films[] = $row;
            }
    
            echo json_encode($films);
            http_response_code(200);
        } else {
            $sql = "SELECT * FROM tbl_films";
            $result = $conn->query($sql);
    
            $films = [];
            while ($row = $result->fetch_assoc()) {
                $films[] = $row;
            }
    
            echo json_encode($films);
            http_response_code(200);
        }
        break;
    

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['title']) && isset($data['genre']) && isset($data['director']) && isset($data['release_year'])) {
            $title = $conn->real_escape_string($data['title']);
            $genre = $conn->real_escape_string($data['genre']);
            $director = $conn->real_escape_string($data['director']);
            $release_year = intval($data['release_year']);
            $synopsis = isset($data['synopsis']) ? $conn->real_escape_string($data['synopsis']) : '';
            $poster_url = isset($data['poster_url']) ? $conn->real_escape_string($data['poster_url']) : '';

            $sql = "INSERT INTO tbl_films (title, genre, director, release_year, synopsis, created_at, poster_url) VALUES ('$title', '$genre', '$director', $release_year, '$synopsis', CURRENT_TIMESTAMP, '$poster_url')";

            if ($conn->query($sql) === TRUE) {
                http_response_code(201);
                echo json_encode(["message" => "Film berhasil ditambahkan", "id" => $conn->insert_id]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Error: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Input tidak lengkap"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'])) {
            $id = intval($data['id']);
            $fields = [];
            if (isset($data['title'])) $fields[] = "title='" . $conn->real_escape_string($data['title']) . "'";
            if (isset($data['genre'])) $fields[] = "genre='" . $conn->real_escape_string($data['genre']) . "'";
            if (isset($data['director'])) $fields[] = "director='" . $conn->real_escape_string($data['director']) . "'";
            if (isset($data['release_year'])) $fields[] = "release_year=" . intval($data['release_year']);
            if (isset($data['synopsis'])) $fields[] = "synopsis='" . $conn->real_escape_string($data['synopsis']) . "'";
            if (isset($data['poster_url'])) $fields[] = "poster_url='" . $conn->real_escape_string($data['poster_url']) . "'";

            $sql = "UPDATE tbl_films SET " . implode(", ", $fields) . " WHERE id=$id";

            if ($conn->query($sql) === TRUE) {
                http_response_code(200);
                echo json_encode(["message" => "Film berhasil diperbarui"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Error: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID tidak ditemukan"]);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "DELETE FROM tbl_films WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                http_response_code(200);
                echo json_encode(["message" => "Film berhasil dihapus"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Error: " . $conn->error]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID tidak valid"]);
        }
        break;
}

$conn->close();
?>
