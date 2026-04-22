<?php 
include 'auth.php'; 
include 'db.php';   
include 'header.php'; 

$message = "";

// 1. Ambil data asal berdasarkan ID yang dihantar melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // Task 3d & 7: Pastikan hanya owner atau admin boleh edit [cite: 48, 85]
    if ($role == 'Admin') {
        $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
    } else {
        $stmt = $conn->prepare("SELECT * FROM services WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if (!$service) {
        header("Location: view_services.php");
        exit();
    }
}

// 2. Proses mengemaskini data (Task 3a) 
if (isset($_POST['update'])) {
    $title = htmlspecialchars($_POST['title']); // Task 7: Prevent XSS 
    $description = htmlspecialchars($_POST['description']);
    $price = $_POST['price'];
    $id = $_POST['id'];

    // Jika ada gambar baru diupload (Task 3c) 
    if ($_FILES['service_image']['name'] != "") {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["service_image"]["name"]);
        $target_file = $target_dir . $file_name;
        
        // Validasi fail (Task 4b) 
        if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file)) {
            $update_sql = "UPDATE services SET title=?, description=?, price=?, image_path=? WHERE id=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssdsi", $title, $description, $price, $target_file, $id);
        }
    } else {
        // Jika gambar tidak ditukar
        $update_sql = "UPDATE services SET title=?, description=?, price=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssdi", $title, $description, $price, $id);
    }

    if ($stmt->execute()) {
        header("Location: view_services.php");
        exit();
    } else {
        $message = "Error updating record.";
    }
}
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h3>Edit Service</h3>
        </div>
        <div class="card-body">
            <?php if ($message != ""): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="edit_service.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Service Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $service['title']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?php echo $service['description']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (RM)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $service['price']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <img src="<?php echo $service['image_path']; ?>" width="150" class="mb-2 rounded shadow-sm">
                    <input type="file" name="service_image" class="form-control">
                    <small class="text-muted">Leave empty if you don't want to change the image.</small>
                </div>
                
                <button type="submit" name="update" class="btn btn-warning">Update Service</button>
                <a href="view_services.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>