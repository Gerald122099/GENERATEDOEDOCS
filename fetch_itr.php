<?php
include 'config.php';
$itr_options = "";
$sql = "SELECT itr_form_number FROM inspection";
$result = $conn->query($sql);



if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['itr_form_number'] . "'>" . $row['itr_form_number'] . "</option>";
        
    }
}
?>

