<?php
include 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get checkbox value (1 for checked, 0 for unchecked)
function getCheckboxValue($postKey) {
    return isset($_POST[$postKey]) ? 1 : 0;
}

// Initialize variables for items and remarks
$item_1_yes = getCheckboxValue('item_1_yes');
$item_1_no = getCheckboxValue('item_1_no');
$remarks_1 = $_POST['remarks_1'];

$item_2_yes = getCheckboxValue('item_2_yes');
$item_2_no = getCheckboxValue('item_2_no');
$remarks_2 = $_POST['remarks_2'];

$item_3_yes = getCheckboxValue('item_3_yes');
$item_3_no = getCheckboxValue('item_3_no');
$remarks_3 = $_POST['remarks_3'];

$item_4_yes = getCheckboxValue('item_4_yes');
$item_4_no = getCheckboxValue('item_4_no');
$remarks_4 = $_POST['remarks_4'];

$item_5_yes = getCheckboxValue('item_5_yes');
$item_5_no = getCheckboxValue('item_5_no');
$remarks_5 = $_POST['remarks_5'];

$item_6_yes = getCheckboxValue('item_6_yes');
$item_6_no = getCheckboxValue('item_6_no');
$remarks_6 = $_POST['remarks_6'];

// SQL query to insert data into the inspection table
$sql = "INSERT INTO inspection (
    item_1_yes, item_1_no, remarks_1,
    item_2_yes, item_2_no, remarks_2,
    item_3_yes, item_3_no, remarks_3,
    item_4_yes, item_4_no, remarks_4,
    item_5_yes, item_5_no, remarks_5,
    item_6_yes, item_6_no, remarks_6
) VALUES (
    '$item_1_yes', '$item_1_no', '$remarks_1',
    '$item_2_yes', '$item_2_no', '$remarks_2',
    '$item_3_yes', '$item_3_no', '$remarks_3',
    '$item_4_yes', '$item_4_no', '$remarks_4',
    '$item_5_yes', '$item_5_no', '$remarks_5',
    '$item_6_yes', '$item_6_no', '$remarks_6'
)";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>