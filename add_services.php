<?php 
// 1. MESTI letak logik PHP di paling atas untuk elak ralat "Header already sent"
include 'auth.php'; 
include 'db.php';   

$message = "";

if (isset($_POST['submit'])) {
    // Task 7: Security (XSS Prevention)
    $title = htmlspecialchars($_POST['title']); 
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id']; // ID user yang sedang login

    // Task 3c: File Upload Logic
    $target_dir = "uploads/";
    
    // Auto-create folder uploads kalau belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES["service_image"]["name"]); 
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Task 4: Server-side Validation
    $check = getimagesize($_FILES["service_image"]["tmp_name"]);
    if($check === false) {
        $message = "File is not an image.";
    } elseif ($_FILES["service_image"]["size"] > 2000000) { 
        $message = "File too large (Max 2MB).";
    } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $message = "Only JPG, JPEG & PNG allowed.";
    } else {
        // Upload file ke folder uploads
       // Cari bahagian move_uploaded_file dalam add_service.php anda
   if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file)) {
    
    // Simpan path relatif ini supaya view_service.php boleh baca
    $path_untuk_db = "uploads/" . $file_name; 
    
    // Pastikan susunan bind_param sepadan dengan struktur table anda:
    // id (auto), user_id (int), title (string), description (string), price (double), image_path (string)
    $stmt = $conn->prepare("INSERT INTO services (user_id, title, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $description, $price, $path_untuk_db);
    
    if ($stmt->execute()) {
        header("Location: view_service.php");
        exit();
    }
}
}
    
}

// Hanya include header selepas logik redirect selesai
include 'header.php'; 
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 py-2">Add Your Skill/Service</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($message != ""): ?>
                        <div class="alert alert-danger"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Service Title</label>
                            <input type="text" name="title" class="form-control" placeholder="E.g: Laptop Repair" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Describe what you can do..." required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price (RM)</label>
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Service Image</label>
                                <input type="file" name="service_image" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" name="submit" class="btn btn-primary px-4">Publish Service</button>
                            <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>