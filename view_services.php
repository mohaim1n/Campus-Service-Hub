<?php 
include 'auth.php'; // Guna nama fail auth anda (auth.php)
include 'db.php';   // Guna nama fail db anda (db.php)
include 'header.php'; 

// Task 6: Gunakan JOIN untuk dapatkan nama pemilik servis
$query = "SELECT services.*, users.username 
          FROM services 
          JOIN users ON services.user_id = users.id 
          ORDER BY services.created_at DESC";
$result = $conn->query($query);
?>

<div class="container mt-4">
    <h2 class="mb-4 text-primary">All Campus Services</h2>
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo $row['image_path']; ?>" class="card-img-top" alt="Service Image" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="text-primary fw-bold">RM <?php echo number_format($row['price'], 2); ?></p>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <small class="text-muted">Posted by: <strong><?php echo htmlspecialchars($row['username']); ?></strong></small>
                            <br>
                            <?php 
                            // Task 3d: Hanya owner atau Admin boleh edit/delete
                            if ($_SESSION['user_id'] == $row['user_id'] || $_SESSION['role'] == 'Admin'): ?>
                                <div class="mt-2">
                                    <a href="edit_service.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete_service.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirm delete?')">Delete</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No services found. Start by adding one!</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>