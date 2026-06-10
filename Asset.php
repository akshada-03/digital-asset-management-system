<?php

class Asset
{
    private $conn;
    private $table = "assets";

    public $id;
    public $category_id;
    public $title;
    public $description;
    public $file_name;
    public $file_type;
    public $file_size;
    public $uploaded_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private $upload_dir = "uploads/";
    private $max_file_size = 10 * 1024 * 1024; // 10 MB
    private $allowed_types = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'video/mp4',
        'text/plain'
    ];

    public function validateFile($file)
    {

        $errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Upload error code: " . $file['error'];
            return $errors;
        }

        if ($file['size'] > $this->max_file_size) {
            $errors[] = "File too large. Maximum size is 10MB. Your file: "
                . round($file['size'] / 1048576, 2) . "MB";
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);


        if (!in_array($mime_type, $this->allowed_types)) {
            $errors[] = "File type not allowed: " . $mime_type
                . ". Allowed: JPG, PNG, GIF, PDF, DOC, DOCX, MP4, TXT";
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            $errors[] = "Security error: Not a valid uploaded file.";
        }

        return $errors;
    }

    public function uploadFile($file)
    {
        $fileName = $file['name'];

        $targetPath = $this->upload_dir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return false;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (category_id, title, description, file_name, file_type, file_size)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->file_name = htmlspecialchars(strip_tags($this->file_name));
        $this->file_type = htmlspecialchars(strip_tags($this->file_type));

        $stmt->bind_param(
            "issssi",
            $this->category_id,
            $this->title,
            $this->description,
            $this->file_name,
            $this->file_type,
            $this->file_size
        );

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll()
    {
        $query = "SELECT
                    a.id,
                    a.title,
                    a.description,
                    a.file_name,
                    a.file_type,
                    a.file_size,
                    a.uploaded_at,
                    a.category_id,
                    c.name AS category_name
                  FROM " . $this->table . " a
                  LEFT JOIN categories c ON a.category_id = c.id
                  ORDER BY a.uploaded_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function readByCategory()
    {
        $query = "SELECT
                    a.id, a.title, a.file_name, a.file_type,
                    a.file_size, a.uploaded_at, c.name AS category_name
                  FROM " . $this->table . " a
                  LEFT JOIN categories c ON a.category_id = c.id
                  WHERE a.category_id = ?
                  ORDER BY a.uploaded_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->category_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function readOne()
    {
        $query = "SELECT
                    a.*, c.name AS category_name
                  FROM " . $this->table . " a
                  LEFT JOIN categories c ON a.category_id = c.id
                  WHERE a.id = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $this->category_id = $row['category_id'];
        $this->title = $row['title'];
        $this->description = $row['description'];
        $this->file_name = $row['file_name'];
        $this->file_type = $row['file_type'];
        $this->file_size = $row['file_size'];
        $this->uploaded_at = $row['uploaded_at'];
    }

    public function update()
    {
        $query = "UPDATE " . $this->table . " SET category_id = ?, title = ?, description = ? WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bind_param(
            "issi",
            $this->category_id,
            $this->title,
            $this->description,
            $this->id
        );

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $this->readOne();
        $file_path = $this->upload_dir . $this->file_name;

        // Delete from database
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            if (file_exists($file_path)) {
                unlink($file_path); 
            }
            return true;
        }
        return false;
    }

    public static function formatSize($bytes)
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . " MB";
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . " KB";
        }
        return $bytes . " bytes";
    }
}

?>

