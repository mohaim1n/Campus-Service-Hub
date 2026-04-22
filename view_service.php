<?php 
// Task 1e & 7: Pastikan sesi dikendalikan dengan selamat
include 'auth.php'; 
include 'db.php';   
include 'header.php'; 

// Task 6: Mendapatkan data menggunakan JOIN untuk paparkan nama owner servis
$query = "SELECT services.*, users.username 
          FROM services 
          JOIN users ON services.user_id = users.id 
          ORDER BY services.created_at DESC";
$result = $conn->query($query);
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary fw-bold mb-0">Campus Service Hub</h2>
            <p class="text-muted">Find or offer skills within the campus community.</p>
        </div>
        <a href="add_service.php" class="btn btn-success btn-lg shadow-sm">+ Add New Service</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-primary text-white border-0"><i class="bi bi-search"></i></span>
                <input type="text" id="searchBar" class="form-control border-0" placeholder="Search services by title or description...">
            </div>
            <div id="searchStatus" class="form-text mt-2 px-2"></div>
        </div>
    </div>

    <div id="serviceList" class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden" style="transition: transform 0.2s;">
                        
                        <?php 
                        // Jika path wujud di folder, guna path itu. Jika tidak, guna placeholder.
                        $display_img = (!empty($row['image_path']) && file_exists($row['image_path'])) 
                                       ? $row['image_path'] 
                                       : 'https://via.placeholder.com/400x250?text=No+Image+Available';
                        ?>
                        <img src="<?php echo $display_img; ?>" class="card-img-top" alt="Service" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold text-dark mb-0"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <span class="badge bg-soft-primary text-primary fs-6">RM <?php echo number_format($row['price'], 2); ?></span>
                            </div>
                            <p class="card-text text-muted small" style="min-height: 50px;"><?php echo htmlspecialchars($row['description']); ?></p>
                            
                            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                                <small class="text-muted">By: <span class="fw-bold text-dark"><?php echo htmlspecialchars($row['username']); ?></span></small>
                                <small class="text-muted italic" style="font-size: 0.75rem;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                            </div>
                        </div>

                        <?php if ($_SESSION['user_id'] == $row['user_id'] || $_SESSION['role'] == 'Admin'): ?>
                        <div class="card-footer bg-light border-0 d-flex gap-2 pb-3">
                            <a href="edit_service.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning w-100">Edit</a>
                            <a href="delete_services.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-danger w-100" 
                               onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <img src="https://illustrations.popsy.co/gray/empty-folder.svg" alt="No data" style="width: 200px;" class="mb-3 opacity-50">
                <h4 class="text-muted">No services found.</h4>
                <p class="text-muted">Be the first to offer a skill in the hub!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('searchBar').addEventListener('input', function() {
    let query = this.value;
    let serviceList = document.getElementById('serviceList');
    let status = document.getElementById('searchStatus');

    if(query.length > 0) {
        status.innerHTML = '<span class="text-primary fw-bold">Searching...</span>';
    } else {
        status.innerHTML = '';
    }

    // Task AJAX: Dapatkan data dari fetch_services.php
    fetch('fetch_services.php?q=' + encodeURIComponent(query))
        .then(response => response.text())
        .then(data => {
            serviceList.innerHTML = data;
            if(query.length > 0) status.innerHTML = 'Showing results for: <span class="text-dark fw-bold">"' + query + '"</span>';
        })
        .catch(error => {
            console.error('Error:', error);
            status.innerHTML = '<span class="text-danger">Error loading results.</span>';
        });
});
</script>

<style>
    .card:hover { transform: translateY(-5px); }
    .bg-soft-primary { background-color: #e7f1ff; }
</style>

<?php include 'footer.php'; ?>