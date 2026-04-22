<?php 
// Sertakan sambungan database dan header
include 'db.php';
include 'header.php'; // Task 2b: Consistent layout 

$message = "";
$messageType = "";

// Task 1: Proses pendaftaran apabila butang ditekan
if (isset($_POST['register'])) {
    
    // Task 7: Ambil data dan cegah XSS menggunakan htmlspecialchars() [cite: 87]
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role']; // Task 1c: Assign TWO roles 

    // Task 4: Server-side validation [cite: 55]
    if (empty($username) || empty($password)) {
        $message = "Please fill in all fields.";
        $messageType = "danger";
    } else {
        // Task 1b: Gunakan password_hash() untuk keselamatan 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Task 7: Gunakan Prepared Statements untuk cegah SQL Injection [cite: 88]
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
            $messageType = "success";
        } else {
            $message = "Error: Username might already be taken.";
            $messageType = "danger";
        }
        $stmt->close();
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Create Account</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($message != ""): ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="register.php" id="regForm">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Role</label>
                            <select name="role" class="form-select" required>
                                <option value="User">User (Student/Provider)</option>
                                <option value="Admin">Admin (Staff)</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="register" class="btn btn-primary">Register Now</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('regForm').onsubmit = function() {
    let pass = document.getElementsByName('password')[0].value;
    if (pass.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }
    return true;
};
</script>

<?php include 'footer.php'; // Task 2b: Consistent layout?>