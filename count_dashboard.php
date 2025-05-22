<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itr_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Main total inspection query (without date filter)
$sql_total = "SELECT COUNT(DISTINCT itr_form_num) AS total_inspections FROM businessinfo";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_inspections = $row_total['total_inspections'];

// Get the selected date range from POST (if any)
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

if ($start_date && $end_date) {
    // Query with dynamic date range filter on time_inserted
    $sql_filtered = "SELECT COUNT(DISTINCT itr_form_num) AS total_inspections_filtered 
                     FROM businessinfo 
                     WHERE time_inserted BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql_filtered);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result_filtered = $stmt->get_result();
    $row_filtered = $result_filtered->fetch_assoc();
    $total_filtered = $row_filtered['total_inspections_filtered'];
} else {
    // If no date filter is applied, just show the total
    $total_filtered = $total_inspections;
}

// Close database connection
$stmt->close();
$conn->close();

// Return the data as JSON for frontend to use
echo json_encode([
    'total_inspections' => $total_inspections,
    'total_filtered' => $total_filtered
]);
?>