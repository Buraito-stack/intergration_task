<?php

require_once './Category.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$response = [];

try {
    $category = new Category();

    if ($action === 'insert') {
        $name = $_POST['name'] ?? '';
        $category->insert($name);
        $response = [
            'status' => 'success',
            'message' => 'Category added successfully',
        ];
    } elseif ($action === 'select') {
        $data = $category->selectAll();
        $response = [
            'status' => 'success',
            'data' => $data,
        ];
    } elseif ($action === 'update') {
        $id = (int)$_POST['id'];
        $name = $_POST['name'] ?? '';
        $category->update($id, $name);
        $response = [
            'status' => 'success',
            'message' => 'Category updated successfully',
        ];
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $category->delete($id);
        $response = [
            'status' => 'success',
            'message' => 'Category deleted successfully',
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Invalid action',
        ];
    }

} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage(),
    ];
}

echo json_encode($response);
