<?php
include('../include/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    // exit;
    $sp_name = $_POST['sp_name'];
    $role = $_POST['role'];
    $company = $_POST['company'];
  

    // Handle file upload
    $target_dir = "uploads/";
    // if (!is_dir($target_dir)) {
    //     mkdir($target_dir, 0777, true);
    // }
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            // Insert into database
            $sql = "INSERT INTO speakers (sp_name, role, company, profile_image) VALUES (:sp_name, :role, :company, :profile_image)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':sp_name' => $sp_name,
                ':role' => $role,
                ':company' => $company,
                ':profile_image' => $target_file
            ]);
            // echo "Speaker added successfully!";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Speaker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-900 text-gray-400">
    <div class="container mx-auto p-8">
        <h2 class="text-white text-2xl mb-4">Add New Speaker</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="sp_name" class="block text-sm">Speaker Name</label>
                <input type="text" id="sp_name" name="sp_name" required
                    class="w-full p-2 bg-gray-800 text-white rounded">
            </div>
            <div>
                <label for="role" class="block text-sm">Role</label>
                <input type="text" id="role" name="role" required class="w-full p-2 bg-gray-800 text-white rounded">
            </div>
            <div>
                <label for="company" class="block text-sm">Company</label>
                <input type="text" id="company" name="company" required
                    class="w-full p-2 bg-gray-800 text-white rounded">
            </div>
            <div>
                <label for="profile_image" class="block text-sm">Profile Image</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" required
                    class="w-full p-2 bg-gray-800 text-white rounded">
            </div>

            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white py-2 px-4 rounded">Submit</button>
        </form>
    </div>
</body>

</html>