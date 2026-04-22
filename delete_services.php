<?php
include 'auth.php';
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // Hanya owner atau admin boleh padam (Task 3d)
    if ($role == 'Admin') {
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
    } else {
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: view_service.php");
    }
    $stmt->close();
}
?>