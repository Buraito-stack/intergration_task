<?php

require_once './Database.php';
require_once './Category.php';

$category = new Category();
$message = '';
$categories = [];
$db_error = '';
$db_success = '';

$db = new Database();
$connectionStatus = $db->getConnectionStatus();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'insert') {
            $name = $_POST['name'] ?? '';
            $category->insert($name);
            $message = 'Category added successfully.';
        } elseif ($action === 'update') {
            $id = (int)$_POST['id'];
            $name = $_POST['name'] ?? '';
            $category->update($id, $name);
            $message = 'Category updated successfully.';
        } elseif ($action === 'delete') {
            $id = (int)$_POST['id'];
            $category->delete($id);
            $message = 'Category deleted successfully.';
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
    }

    header("Location: index.php");
    exit;
}

try {
    $categories = $category->selectAll();
    if (!is_array($categories)) {
        $categories = [];
    }
} catch (Exception $e) {
    $db_error = 'Error fetching categories: ' . $e->getMessage();
}

if (isset($connectionStatus['error'])) {
    $db_error = $connectionStatus['error'];
}
if (isset($connectionStatus['success'])) {
    $db_success = $connectionStatus['success'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .notification {
            display: none; 
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            border-radius: 0.375rem; 
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-center text-yellow-600 mb-10">CATEGORY MANAGEMENT</h1>

        <!-- Display database connection messages -->
        <?php if ($db_error): ?>
            <div id="dbError" class="notification bg-red-500 text-white p-4 mb-6 rounded">
                <?php echo htmlspecialchars($db_error); ?>
            </div>
        <?php endif; ?>
        <?php if ($db_success): ?>
            <div id="dbSuccess" class="notification bg-green-500 text-white p-4 mb-6 rounded">
                <?php echo htmlspecialchars($db_success); ?>
            </div>
        <?php endif; ?>

        <!-- Display CRUD operation messages -->
        <?php if ($message): ?>
            <div id="crudMessage" class="notification bg-blue-500 text-white p-4 mb-6 rounded">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-wrap">
            <!-- Input Section -->
            <div class="w-full md:w-2/3 p-4 bg-gray-200 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">Add/Update Category</h2>
                <form action="index.php" method="post" class="mb-6">
                    <input type="hidden" name="action" value="insert">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Category Name:</label>
                        <input type="text" id="name" name="name" required class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Category</button>
                </form>

                <form action="index.php" method="post" class="mb-6">
                    <input type="hidden" name="action" value="update">
                    <div class="mb-4">
                        <label for="update-id" class="block text-gray-700">Category ID:</label>
                        <input type="number" id="update-id" name="id" required class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <label for="update-name" class="block text-gray-700">New Category Name:</label>
                        <input type="text" id="update-name" name="name" required class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update Category</button>
                </form>

                <form action="index.php" method="post">
                    <input type="hidden" name="action" value="delete">
                    <div class="mb-4">
                        <label for="delete-id" class="block text-gray-700">Category ID:</label>
                        <input type="number" id="delete-id" name="id" required class="w-full p-2 border border-gray-300 rounded">
                    </div>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete Category</button>
                </form>
            </div>

            <!-- Table Section -->
            <div class="w-full md:w-1/3 p-4">
                <h2 class="text-xl font-semibold mb-2">All Categories</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categories) && is_array($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr class="border-t">
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($cat['id']); ?></td>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($cat['name']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="px-4 py-2 text-center">No categories found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dbError = document.getElementById('dbError');
            var dbSuccess = document.getElementById('dbSuccess');
            var crudMessage = document.getElementById('crudMessage');
            
            if (dbError) {
                dbError.style.display = 'block';
                setTimeout(function() {
                    dbError.style.display = 'none';
                }, 5000); 
            }
            
            if (dbSuccess) {
                dbSuccess.style.display = 'block';
                setTimeout(function() {
                    dbSuccess.style.display = 'none';
                }, 5000); 
            }

            if (crudMessage) {
                crudMessage.style.display = 'block';
                setTimeout(function() {
                    crudMessage.style.display = 'none';
                }, 5000); 
            }
        });
    </script>
</body>
</html>
