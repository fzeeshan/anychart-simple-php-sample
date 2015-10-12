<?php
require './data/Data.php';

$data = new \Data();

header('Content-Type: application/json');
echo json_encode(array(
    'years' => $data->years(),
    'quarters' => $data->quarters(),
    'products' => $data->products(),
    'industries' => $data->industries(),
    'sales_reps' => $data->salesReps(),
    'regions' => $data->regions()));
?>
