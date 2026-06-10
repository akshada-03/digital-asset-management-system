<?php

class Category
{
    private $conn;
    private $table = "categories";
    public $id;
    public $name;
    public $description;
    public $created_at;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (name, description) VALUES (?, ?)";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bind_param("ss", $this->name, $this->description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->get_result();

    }

    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);

        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->created_at = $row['created_at'];

    }

    public function update()
    {
        $query = "UPDATE " . $this->table . " SET name = ?, description = ? WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bind_param("ssi", $this->name, $this->description, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function countAssets()
    {
        $query = "SELECT COUNT(*) as total FROM assets WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>



