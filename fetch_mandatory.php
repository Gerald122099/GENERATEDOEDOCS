<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "inspection_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "SELECT id, itr_form_number, business_name, dealer_operator, location, in_charge, designation, inspection_datetime, 
               item_1, item_2, item_3, item_4, item_5, item_6, item_7, item_8, item_9, item_10, 
               item_11, item_12, item_13, item_14, item_15, item_16, item_17, item_18, item_19, item_20, 
               item_21, item_22, item_23, item_24, item_25, item_26, item_27, item_28, item_29, item_30, 
               item_31, item_32, item_33, item_34, item_35, item_36, item_37, item_38, item_39, item_40, 
               item_41, item_42, item_43, item_44, item_45 
        FROM inspection";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Business Name</th><th>Dealer Operator</th><th>Location</th><th>In Charge</th><th>Designation</th><th>Inspection Date</th>";

    // Display items 1 to 45 as table headers
    for ($i = 1; $i <= 45; $i++) {
        echo "<th>Item $i</th>";
    }

    echo "</tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["business_name"] . "</td><td>" . $row["dealer_operator"] . "</td><td>" . $row["location"] . "</td><td>" . $row["in_charge"] . "</td><td>" . $row["designation"] . "</td><td>" . $row["inspection_datetime"] . "</td>";

        for ($i = 1; $i <= 45; $i++) {
            echo "<td>" . $row["item_" . $i] . "</td>";
        }

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No results found";
}

$conn->close();
?>