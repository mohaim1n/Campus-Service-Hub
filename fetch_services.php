<?php
include 'db.php';
$search = isset($_GET['q']) ? $_GET['q'] : '';

$query = "SELECT services.*, users.username FROM services 
          JOIN users ON services.user_id = users.id 
          WHERE title LIKE ? OR description LIKE ? 
          ORDER BY services.created_at DESC";

$stmt = $conn->prepare($query);
$searchTerm = "%$search%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    // Kod HTML untuk paparan kad servis (boleh copy dari view_services anda)
    echo '<div class="col-md-4 mb-4">...</div>';
}
?>