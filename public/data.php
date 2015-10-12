<?php
require './data/Data.php';

$data = new \Data('anychart_sample', 'user', 'pass');
$params = json_decode(file_get_contents("php://input"), true);

$args = [$params['years'],
         $params['quarters'],
         $params['products'],
         $params['regions'],
         $params['industries'],
         $params['sales']];
         
header('Content-Type: application/json');
echo json_encode(array(
    'by_industry' => call_user_func_array([$data, 'revenueByIndustry'], $args),
    'by_product' => call_user_func_array([$data, 'revenueByProduct'], $args),
    'by_sales' => call_user_func_array([$data, 'revenueBySalesRep'], $args),
    'by_quarter' => call_user_func_array([$data, 'revenueByQuarter'], $args)
));
?>
