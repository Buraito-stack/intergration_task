<?php

require_once './Category.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$response = [];

try {
    $category = new Category();

    switch ($action) {
        case 'insert':
            $name = $_POST['name'] ?? '';
            $category->insert($name);
            $response = ['status' => 'success', 'message' => 'Category added successfully'];
            break;
        case 'select':
            $data = $category->selectAll();
            $response = ['status' => 'success', 'data' => $data];
            break;
        case 'update':
            $id = (int)$_POST['id'];
            $name = $_POST['name'] ?? '';
            $category->update($id, $name);
            $response = ['status' => 'success', 'message' => 'Category updated successfully'];
            break;
        case 'delete':
            $id = (int)$_POST['id'];
            $category->delete($id);
            $response = ['status' => 'success', 'message' => 'Category deleted successfully'];
            break;
        default:
            $response = ['status' => 'error', 'message' => 'Invalid action'];
            break;
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
