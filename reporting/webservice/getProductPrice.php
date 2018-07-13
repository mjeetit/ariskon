<?php
header('Content-type: application/json');
$product_id = isset($_REQUEST['product'])?$_REQUEST['product']:0;
$units = isset($_REQUEST['Unitvalue'])?$_REQUEST['Unitvalue']:0;
include 'config.php';

$sql = "SELECT mrp_incl_vat FROM crm_products WHERE product_id='".$product_id."'";
$execute = mysql_query($sql);
$products = mysql_fetch_assoc($execute);
$product_price = ($units * (($products['mrp_incl_vat']>0)?$products['mrp_incl_vat']:0));
echo json_encode(array('success'=>'YES','Price'=>$product_price)); exit;
?>