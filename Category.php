<?php

require_once './Database.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function insert($name) {
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    public function update($id, $name) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function selectAll() {
        $result = $this->db->query("SELECT * FROM categories");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
