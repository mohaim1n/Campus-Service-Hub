<?php 
include 'auth.php'; // Pastikan user dah login
include 'db.php';
include 'header.php'; 

$user_role = $_SESSION['role'];
$username = $_SESSION['username'];
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="p-5 mb-4 bg-light rounded-3 shadow-sm border">
                <div class="container-fluid py-2">
                    <h1 class="display-5 fw-bold text-primary">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                    <p class="col-md-8 fs-4">You are logged in as: <strong><?php echo $user_role; ?></strong></p>
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <a href="view_services.php" class="btn btn-outline-primary btn-lg">View Services</a>
                        
                        <?php if ($user_role == 'Admin'): ?>
                            <a href="manage_users.php" class="btn btn-danger btn-lg">Manage All Users</a>
                        <?php endif; ?>
                        
                        <a href="add_services.php" class="btn btn-success btn-lg">+ Add New Service</a>
                        <a href="logout.php" class="btn btn-secondary btn-lg">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-center mt-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-primary">
                <h3>My Services</h3>
                <p class="fs-2 fw-bold text-primary">
                    <?php
                    // Task 6: Database logic to count services
                    $uid = $_SESSION['user_id'];
                    $sql = ($user_role == 'Admin') ? "SELECT COUNT(*) as total FROM services" : "SELECT COUNT(*) as total FROM services WHERE user_id = $uid";
                    $res = $conn->query($sql);
                    echo $res->fetch_assoc()['total'];
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>