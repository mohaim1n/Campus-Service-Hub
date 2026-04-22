<?php 
// Task 1d & 1e: Pastikan hanya Admin boleh akses halaman ini
include 'auth.php'; 
include 'db.php';   
include 'header.php'; 

if ($_SESSION['role'] !== 'Admin') {
    header("Location: dashboard.php");
    exit();
}

// Task 6: Paparkan data dari database MySQL 
$query = "SELECT id, username, role, created_at FROM users ORDER BY id DESC";
$result = $conn->query($query);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">User Management Control</h2>
        <span class="badge bg-danger p-2">Admin Mode</span>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Date Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $user['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                            <td>
                                <span class="badge <?php echo ($user['role'] == 'Admin') ? 'bg-danger' : 'bg-info text-dark'; ?>">
                                    <?php echo $user['role']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Adakah anda pasti mahu memadam user ini? Semua servis berkaitan juga akan dipadam.')">
                                       Delete Account
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small italic">Current Session</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php include 'footer.php'; ?>