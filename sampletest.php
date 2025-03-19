

<?php 


$data = [
  ['id' => 1, 'name' => 'John', 'age' => 30],
  ['id' => 2, 'name' => 'Jane', 'age' => 25]
];

$num_columns = count(array_keys($data[0])); // Get number of columns

for ($i = 0; $i < $num_columns; $i++) {
  // Access column data using index
  $column_name = array_keys($data[0])[$i];
  echo "Column: " . $column_name . "<br>";
}
    ?>