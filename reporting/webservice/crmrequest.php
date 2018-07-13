<?php
header('Content-type: application/json');
$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
include 'config.php';
//$connect = mysql_connect('localhost','','');
//mysql_select_db('',$connect);

     $doctor_id = isset($_REQUEST['doctor_id'])?$_REQUEST['doctor_id']:0;
	 $activitynature_id = isset($_REQUEST['activitynature_id'])?$_REQUEST['activitynature_id']:'3';
	 $activity_cost = isset($_REQUEST['activity_cost'])?$_REQUEST['activity_cost']:'';
	 $activity_detail = isset($_REQUEST['activity_detail'])?$_REQUEST['activity_detail']:'';
	 $dd_chequedetail = isset($_REQUEST['dd_chequedetail'])?$_REQUEST['dd_chequedetail']:'';
	 $paybleat = isset($_REQUEST['paybleat'])?$_REQUEST['paybleat']:'';
	 
	 $chemistlist = getChemist($doctor_id);
	 
	 
	 $product1 = isset($_REQUEST['product1'])?$_REQUEST['product1']:'0';
	 $product2 = isset($_REQUEST['product2'])?$_REQUEST['product2']:'0';
	 $product3 = isset($_REQUEST['product3'])?$_REQUEST['product3']:'0';
	 $product4 = isset($_REQUEST['product4'])?$_REQUEST['product4']:'0';
	 $product5 = isset($_REQUEST['product5'])?$_REQUEST['product5']:'0';
	 $product6 = isset($_REQUEST['product6'])?$_REQUEST['product6']:'0';
	 $product7 = isset($_REQUEST['product7'])?$_REQUEST['product7']:'0';
	 $productArr = array_filter(array($product1,$product2,$product3,$product4,$product5,$product6,$product7));
	 $sql= "SELECT * FROM crm_products WHERE product_id IN(".implode(",",$productArr).")";
	 $execute = mysql_query($sql);
	 $productPrices = array();
	 while($result = mysql_fetch_assoc($execute)){
	   $productPrices[$result['product_id']] = $result['mrp_incl_vat'];
	 }
	 $monthproductunit = array();
	 $month1 = date('Y-m-d', strtotime(date('Y-m')." -3 month"));
	 $month2 = date('Y-m-d', strtotime(date('Y-m')." -2 month"));
	 $month3 = date('Y-m-d', strtotime(date('Y-m')." -1 month"));
	 $month4 = date('Y-m-d', strtotime(date('Y-m')."  0 month"));
	 $month5 = date('Y-m-d', strtotime(date('Y-m')." +1 month"));
	 $month6 = date('Y-m-d', strtotime(date('Y-m')." +2 month"));
	 $month7 = date('Y-m-d', strtotime(date('Y-m')." +3 month"));
	 $month8 = date('Y-m-d', strtotime(date('Y-m')." +4 month"));
	 $month9 = date('Y-m-d', strtotime(date('Y-m')." +5 month"));
	 
	 
	 //
	 $product1unit1 = isset($_REQUEST['product1unit1'])?$_REQUEST['product1unit1']:'0';
	 $product1unit2 = isset($_REQUEST['product1unit2'])?$_REQUEST['product1unit2']:'0';
	 $product1unit3 = isset($_REQUEST['product1unit3'])?$_REQUEST['product1unit3']:'0';
	 $product1unit4 = isset($_REQUEST['product1unit4'])?$_REQUEST['product1unit4']:'0';
	 $product1unit5 = isset($_REQUEST['product1unit5'])?$_REQUEST['product1unit5']:'0';
	 $product1unit6 = isset($_REQUEST['product1unit6'])?$_REQUEST['product1unit6']:'0';
	 $product1unit7 = isset($_REQUEST['product1unit7'])?$_REQUEST['product1unit7']:'0';
	 
	 if($product1>0){
	   $productMonth[$month1][$product1] = array('ProductUnit'=>$product1unit1,'ProductValue'=>$productPrices[$product1]*$product1unit1);
	   $productMonth[$month2][$product1] = array('ProductUnit'=>$product1unit2,'ProductValue'=>$productPrices[$product1]*$product1unit2);
	   $productMonth[$month3][$product1] = array('ProductUnit'=>$product1unit3,'ProductValue'=>$productPrices[$product1]*$product1unit3);
	   $productMonth[$month4][$product1] = array('ProductUnit'=>$product1unit4,'ProductValue'=>$productPrices[$product1]*$product1unit4);
	   $productMonth[$month5][$product1] = array('ProductUnit'=>$product1unit5,'ProductValue'=>$productPrices[$product1]*$product1unit5);
	   $productMonth[$month6][$product1] = array('ProductUnit'=>$product1unit6,'ProductValue'=>$productPrices[$product1]*$product1unit6);
	   $productMonth[$month7][$product1] = array('ProductUnit'=>$product1unit7,'ProductValue'=>$productPrices[$product1]*$product1unit7);
	   $productMonth[$month8][$product1] = array('ProductUnit'=>$product1unit8,'ProductValue'=>$productPrices[$product1]*$product1unit8);
	   $productMonth[$month9][$product1] = array('ProductUnit'=>$product1unit9,'ProductValue'=>$productPrices[$product1]*$product1unit9);
	   /*$monthproductunit['product1'] = array($month1=>array('Product'=>$product1,'ProductUnit'=>$product1unit1,'ProductValue'=>$productPrices[$product1]*$product1unit1),
	   				 	   $month2=>array('Product'=>$product1,'ProductUnit'=>$product1unit2,'ProductValue'=>$productPrices[$product1]*$product1unit2),
						   $month3=>array('Product'=>$product1,'ProductUnit'=>$product1unit3,'ProductValue'=>$productPrices[$product1]*$product1unit3),
						   $month4=>array('Product'=>$product1,'ProductUnit'=>$product1unit4,'ProductValue'=>$productPrices[$product1]*$product1unit4),
						   $month5=>array('Product'=>$product1,'ProductUnit'=>$product1unit5,'ProductValue'=>$productPrices[$product1]*$product1unit5),
						   $month6=>array('Product'=>$product1,'ProductUnit'=>$product1unit6,'ProductValue'=>$productPrices[$product1]*$product1unit6),
						   $month7=>array('Product'=>$product1,'ProductUnit'=>$product1unit7,'ProductValue'=>$productPrices[$product1]*$product1unit7),
						   $month8=>array('Product'=>$product1,'ProductUnit'=>$product1unit8,'ProductValue'=>$productPrices[$product1]*$product1unit8),
						   $month9=>array('Product'=>$product1,'ProductUnit'=>$product1unit9,'ProductValue'=>$productPrices[$product1]*$product1unit9));*/
	 }
	 //
	 $product2unit1 = isset($_REQUEST['product2unit1'])?$_REQUEST['product2unit1']:'0';
	 $product2unit2 = isset($_REQUEST['product2unit2'])?$_REQUEST['product2unit2']:'0';
	 $product2unit3 = isset($_REQUEST['product2unit3'])?$_REQUEST['product2unit3']:'0';
	 $product2unit4 = isset($_REQUEST['product2unit4'])?$_REQUEST['product2unit4']:'0';
	 $product2unit5 = isset($_REQUEST['product2unit5'])?$_REQUEST['product2unit5']:'0';
	 $product2unit6 = isset($_REQUEST['product2unit6'])?$_REQUEST['product2unit6']:'0';
	 $product2unit7 = isset($_REQUEST['product2unit7'])?$_REQUEST['product2unit7']:'0';
	 if($product2>0){
	   $productMonth[$month1][$product2] = array('ProductUnit'=>$product2unit1,'ProductValue'=>$productPrices[$product2]*$product2unit1);
	   $productMonth[$month2][$product2] = array('ProductUnit'=>$product2unit2,'ProductValue'=>$productPrices[$product2]*$product2unit2);
	   $productMonth[$month3][$product2] = array('ProductUnit'=>$product2unit3,'ProductValue'=>$productPrices[$product2]*$product2unit3);
	   $productMonth[$month4][$product2] = array('ProductUnit'=>$product2unit4,'ProductValue'=>$productPrices[$product2]*$product2unit4);
	   $productMonth[$month5][$product2] = array('ProductUnit'=>$product2unit5,'ProductValue'=>$productPrices[$product2]*$product2unit5);
	   $productMonth[$month6][$product2] = array('ProductUnit'=>$product2unit6,'ProductValue'=>$productPrices[$product2]*$product2unit6);
	   $productMonth[$month7][$product2] = array('ProductUnit'=>$product2unit7,'ProductValue'=>$productPrices[$product2]*$product2unit7);
	   $productMonth[$month8][$product2] = array('ProductUnit'=>$product2unit8,'ProductValue'=>$productPrices[$product2]*$product2unit8);
	   $productMonth[$month9][$product2] = array('ProductUnit'=>$product2unit9,'ProductValue'=>$productPrices[$product2]*$product2unit9);
	   /*$monthproductunit['product2'] = array($month1=>array('Product'=>$product2,'ProductUnit'=>$product2unit1,'ProductValue'=>$productPrices[$product2]*$product2unit1),
	   				 	   $month2=>array('Product'=>$product2,'ProductUnit'=>$product2unit2,'ProductValue'=>$productPrices[$product2]*$product2unit2),
						   $month3=>array('Product'=>$product2,'ProductUnit'=>$product2unit3,'ProductValue'=>$productPrices[$product2]*$product2unit3),
						   $month4=>array('Product'=>$product2,'ProductUnit'=>$product2unit4,'ProductValue'=>$productPrices[$product2]*$product2unit4),
						   $month5=>array('Product'=>$product2,'ProductUnit'=>$product2unit5,'ProductValue'=>$productPrices[$product2]*$product2unit5),
						   $month6=>array('Product'=>$product2,'ProductUnit'=>$product2unit6,'ProductValue'=>$productPrices[$product2]*$product2unit6),
						   $month7=>array('Product'=>$product2,'ProductUnit'=>$product2unit7,'ProductValue'=>$productPrices[$product2]*$product2unit7),
						   $month8=>array('Product'=>$product2,'ProductUnit'=>$product2unit8,'ProductValue'=>$productPrices[$product2]*$product2unit8),
						   $month9=>array('Product'=>$product2,'ProductUnit'=>$product2unit9,'ProductValue'=>$productPrices[$product2]*$product2unit9));*/
	 }
	 //
	 $product3unit1 = isset($_REQUEST['product3unit1'])?$_REQUEST['product3unit1']:'0';
	 $product3unit2 = isset($_REQUEST['product3unit2'])?$_REQUEST['product3unit2']:'0';
	 $product3unit3 = isset($_REQUEST['product3unit3'])?$_REQUEST['product3unit3']:'0';
	 $product3unit4 = isset($_REQUEST['product3unit4'])?$_REQUEST['product3unit4']:'0';
	 $product3unit5 = isset($_REQUEST['product3unit5'])?$_REQUEST['product3unit5']:'0';
	 $product3unit6 = isset($_REQUEST['product3unit6'])?$_REQUEST['product3unit6']:'0';
	 $product3unit7 = isset($_REQUEST['product3unit7'])?$_REQUEST['product3unit7']:'0';
	 if($product3>0){
	   $productMonth[$month1][$product3] = array('ProductUnit'=>$product3unit1,'ProductValue'=>$productPrices[$product3]*$product3unit1);
	   $productMonth[$month2][$product3] = array('ProductUnit'=>$product3unit2,'ProductValue'=>$productPrices[$product3]*$product3unit2);
	   $productMonth[$month3][$product3] = array('ProductUnit'=>$product3unit3,'ProductValue'=>$productPrices[$product3]*$product3unit3);
	   $productMonth[$month4][$product3] = array('ProductUnit'=>$product3unit4,'ProductValue'=>$productPrices[$product3]*$product3unit4);
	   $productMonth[$month5][$product3] = array('ProductUnit'=>$product3unit5,'ProductValue'=>$productPrices[$product3]*$product3unit5);
	   $productMonth[$month6][$product3] = array('ProductUnit'=>$product3unit6,'ProductValue'=>$productPrices[$product3]*$product3unit6);
	   $productMonth[$month7][$product3] = array('ProductUnit'=>$product3unit7,'ProductValue'=>$productPrices[$product3]*$product3unit7);
	   $productMonth[$month8][$product3] = array('ProductUnit'=>$product3unit8,'ProductValue'=>$productPrices[$product3]*$product3unit8);
	   $productMonth[$month9][$product3] = array('ProductUnit'=>$product3unit9,'ProductValue'=>$productPrices[$product3]*$product3unit9);
	   
	  /* $monthproductunit['product3'] = array($month1=>array('Product'=>$product3,'ProductUnit'=>$product3unit1,'ProductValue'=>$productPrices[$product3]*$product3unit1),
	   				 	   $month2=>array('Product'=>$product3,'ProductUnit'=>$product3unit2,'ProductValue'=>$productPrices[$product3]*$product3unit2),
						   $month3=>array('Product'=>$product3,'ProductUnit'=>$product3unit3,'ProductValue'=>$productPrices[$product3]*$product3unit3),
						   $month4=>array('Product'=>$product3,'ProductUnit'=>$product3unit4,'ProductValue'=>$productPrices[$product3]*$product3unit4),
						   $month5=>array('Product'=>$product3,'ProductUnit'=>$product3unit5,'ProductValue'=>$productPrices[$product3]*$product3unit5),
						   $month6=>array('Product'=>$product3,'ProductUnit'=>$product3unit6,'ProductValue'=>$productPrices[$product3]*$product3unit6),
						   $month7=>array('Product'=>$product3,'ProductUnit'=>$product3unit7,'ProductValue'=>$productPrices[$product3]*$product3unit7),
						   $month8=>array('Product'=>$product3,'ProductUnit'=>$product3unit8,'ProductValue'=>$productPrices[$product3]*$product3unit8),
						   $month9=>array('Product'=>$product3,'ProductUnit'=>$product3unit9,'ProductValue'=>$productPrices[$product3]*$product3unit9));*/
	 }
	 //
	 $product4unit1 = isset($_REQUEST['product4unit1'])?$_REQUEST['product4unit1']:'0';
	 $product4unit2 = isset($_REQUEST['product4unit2'])?$_REQUEST['product4unit2']:'0';
	 $product4unit3 = isset($_REQUEST['product4unit3'])?$_REQUEST['product4unit3']:'0';
	 $product4unit4 = isset($_REQUEST['product4unit4'])?$_REQUEST['product4unit4']:'0';
	 $product4unit5 = isset($_REQUEST['product4unit5'])?$_REQUEST['product4unit5']:'0';
	 $product4unit6 = isset($_REQUEST['product4unit6'])?$_REQUEST['product4unit6']:'0';
	 $product4unit7 = isset($_REQUEST['product4unit7'])?$_REQUEST['product4unit7']:'0';
	 if($product4>0){
	    $productMonth[$month1][$product4] = array('ProductUnit'=>$product4unit1,'ProductValue'=>$productPrices[$product4]*$product4unit1);
		$productMonth[$month2][$product4] = array('ProductUnit'=>$product4unit2,'ProductValue'=>$productPrices[$product4]*$product4unit2);
		$productMonth[$month3][$product4] = array('ProductUnit'=>$product4unit3,'ProductValue'=>$productPrices[$product4]*$product4unit3);
		$productMonth[$month4][$product4] = array('ProductUnit'=>$product4unit4,'ProductValue'=>$productPrices[$product4]*$product4unit4);
		$productMonth[$month5][$product4] = array('ProductUnit'=>$product4unit5,'ProductValue'=>$productPrices[$product4]*$product4unit5);
		$productMonth[$month6][$product4] = array('ProductUnit'=>$product4unit6,'ProductValue'=>$productPrices[$product4]*$product4unit6);
		$productMonth[$month7][$product4] = array('ProductUnit'=>$product4unit7,'ProductValue'=>$productPrices[$product4]*$product4unit7);
		$productMonth[$month8][$product4] = array('ProductUnit'=>$product4unit8,'ProductValue'=>$productPrices[$product4]*$product4unit8);
		$productMonth[$month9][$product4] = array('ProductUnit'=>$product4unit9,'ProductValue'=>$productPrices[$product4]*$product4unit9);
	   /*$monthproductunit['product4'] = array($month1=>array('Product'=>$product4,'ProductUnit'=>$product4unit1,'ProductValue'=>$productPrices[$product4]*$product4unit1),
	   				 	   $month2=>array('Product'=>$product4,'ProductUnit'=>$product4unit2,'ProductValue'=>$productPrices[$product4]*$product4unit2),
						   $month3=>array('Product'=>$product4,'ProductUnit'=>$product4unit3,'ProductValue'=>$productPrices[$product4]*$product4unit3),
						   $month4=>array('Product'=>$product4,'ProductUnit'=>$product4unit4,'ProductValue'=>$productPrices[$product4]*$product4unit4),
						   $month5=>array('Product'=>$product4,'ProductUnit'=>$product4unit5,'ProductValue'=>$productPrices[$product4]*$product4unit5),
						   $month6=>array('Product'=>$product4,'ProductUnit'=>$product4unit6,'ProductValue'=>$productPrices[$product4]*$product4unit6),
						   $month7=>array('Product'=>$product4,'ProductUnit'=>$product4unit7,'ProductValue'=>$productPrices[$product4]*$product4unit7),
						   $month8=>array('Product'=>$product4,'ProductUnit'=>$product4unit8,'ProductValue'=>$productPrices[$product4]*$product4unit8),
						   $month9=>array('Product'=>$product4,'ProductUnit'=>$product4unit9,'ProductValue'=>$productPrices[$product4]*$product4unit9));*/
	 }
	 //
	 $product5unit1 = isset($_REQUEST['product5unit1'])?$_REQUEST['product5unit1']:'0';
	 $product5unit2 = isset($_REQUEST['product5unit2'])?$_REQUEST['product5unit2']:'0';
	 $product5unit3 = isset($_REQUEST['product5unit3'])?$_REQUEST['product5unit3']:'0';
	 $product5unit4 = isset($_REQUEST['product5unit4'])?$_REQUEST['product5unit4']:'0';
	 $product5unit5 = isset($_REQUEST['product5unit5'])?$_REQUEST['product5unit5']:'0';
	 $product5unit6 = isset($_REQUEST['product5unit6'])?$_REQUEST['product5unit6']:'0';
	 $product5unit7 = isset($_REQUEST['product5unit7'])?$_REQUEST['product5unit7']:'0';
	 if($product5>0){
	   $productMonth[$month1][$product5] = array('ProductUnit'=>$product5unit1,'ProductValue'=>$productPrices[$product5]*$product5unit1);
	   $productMonth[$month2][$product5] = array('ProductUnit'=>$product5unit2,'ProductValue'=>$productPrices[$product5]*$product5unit2);
	   $productMonth[$month3][$product5] = array('ProductUnit'=>$product5unit3,'ProductValue'=>$productPrices[$product5]*$product5unit3);
	   $productMonth[$month4][$product5] = array('ProductUnit'=>$product5unit4,'ProductValue'=>$productPrices[$product5]*$product5unit4);
	   $productMonth[$month5][$product5] = array('ProductUnit'=>$product5unit5,'ProductValue'=>$productPrices[$product5]*$product5unit5);
	   $productMonth[$month6][$product5] = array('ProductUnit'=>$product5unit6,'ProductValue'=>$productPrices[$product5]*$product5unit6);
	   $productMonth[$month7][$product5] = array('ProductUnit'=>$product5unit7,'ProductValue'=>$productPrices[$product5]*$product5unit7);
	   $productMonth[$month8][$product5] = array('ProductUnit'=>$product5unit8,'ProductValue'=>$productPrices[$product5]*$product5unit8);
	   $productMonth[$month9][$product5] = array('ProductUnit'=>$product5unit9,'ProductValue'=>$productPrices[$product5]*$product5unit9);
	   /*$monthproductunit['product5'] = array('Product'=>$product5,$month1=>array('ProductUnit'=>$product5unit1,'ProductValue'=>$productPrices[$product5]*$product5unit1),
	   				 	   $month2=>array('Product'=>$product5,'ProductUnit'=>$product5unit2,'ProductValue'=>$productPrices[$product5]*$product5unit2),
						   $month3=>array('Product'=>$product5,'ProductUnit'=>$product5unit3,'ProductValue'=>$productPrices[$product5]*$product5unit3),
						   $month4=>array('Product'=>$product5,'ProductUnit'=>$product5unit4,'ProductValue'=>$productPrices[$product5]*$product5unit4),
						   $month5=>array('Product'=>$product5,'ProductUnit'=>$product5unit5,'ProductValue'=>$productPrices[$product5]*$product5unit5),
						   $month6=>array('Product'=>$product5,'ProductUnit'=>$product5unit6,'ProductValue'=>$productPrices[$product5]*$product5unit6),
						   $month7=>array('Product'=>$product5,'ProductUnit'=>$product5unit7,'ProductValue'=>$productPrices[$product5]*$product5unit7),
						   $month8=>array('Product'=>$product5,'ProductUnit'=>$product5unit8,'ProductValue'=>$productPrices[$product5]*$product5unit8),
						   $month9=>array('Product'=>$product5,'ProductUnit'=>$product5unit9,'ProductValue'=>$productPrices[$product5]*$product5unit9));*/
	 }
	 //
	 $product6unit1 = isset($_REQUEST['product6unit1'])?$_REQUEST['product6unit1']:'0';
	 $product6unit2 = isset($_REQUEST['product6unit2'])?$_REQUEST['product6unit2']:'0';
	 $product6unit3 = isset($_REQUEST['product6unit3'])?$_REQUEST['product6unit3']:'0';
	 $product6unit4 = isset($_REQUEST['product6unit4'])?$_REQUEST['product6unit4']:'0';
	 $product6unit5 = isset($_REQUEST['product6unit5'])?$_REQUEST['product6unit5']:'0';
	 $product6unit6 = isset($_REQUEST['product6unit6'])?$_REQUEST['product6unit6']:'0';
	 $product6unit7 = isset($_REQUEST['product6unit7'])?$_REQUEST['product6unit7']:'0';
	 if($product6>0){
	    $productMonth[$month1][$product6] = array('ProductUnit'=>$product6unit1,'ProductValue'=>$productPrices[$product6]*$product6unit1);
		$productMonth[$month2][$product6] = array('ProductUnit'=>$product6unit2,'ProductValue'=>$productPrices[$product6]*$product6unit2);
		$productMonth[$month3][$product6] = array('ProductUnit'=>$product6unit3,'ProductValue'=>$productPrices[$product6]*$product6unit3);
		$productMonth[$month4][$product6] = array('ProductUnit'=>$product6unit4,'ProductValue'=>$productPrices[$product6]*$product6unit4);
		$productMonth[$month5][$product6] = array('ProductUnit'=>$product6unit5,'ProductValue'=>$productPrices[$product6]*$product6unit5);
		$productMonth[$month6][$product6] = array('ProductUnit'=>$product6unit6,'ProductValue'=>$productPrices[$product6]*$product6unit6);
		$productMonth[$month7][$product6] = array('ProductUnit'=>$product6unit7,'ProductValue'=>$productPrices[$product6]*$product6unit7);
		$productMonth[$month8][$product6] = array('ProductUnit'=>$product6unit8,'ProductValue'=>$productPrices[$product6]*$product6unit8);
		$productMonth[$month9][$product6] = array('ProductUnit'=>$product6unit9,'ProductValue'=>$productPrices[$product6]*$product6unit9);
	   
	   /*$monthproductunit['product6'] = array($month1=>array('Product'=>$product6,'ProductUnit'=>$product6unit1,'ProductValue'=>$productPrices[$product6]*$product6unit1),
	   				 	   $month2=>array('Product'=>$product6,'ProductUnit'=>$product6unit2,'ProductValue'=>$productPrices[$product6]*$product6unit2),
						   $month3=>array('Product'=>$product6,'ProductUnit'=>$product6unit3,'ProductValue'=>$productPrices[$product6]*$product6unit3),
						   $month4=>array('Product'=>$product6,'ProductUnit'=>$product6unit4,'ProductValue'=>$productPrices[$product6]*$product6unit4),
						   $month5=>array('Product'=>$product6,'ProductUnit'=>$product6unit5,'ProductValue'=>$productPrices[$product6]*$product6unit5),
						   $month6=>array('Product'=>$product6,'ProductUnit'=>$product6unit6,'ProductValue'=>$productPrices[$product6]*$product6unit6),
						   $month7=>array('Product'=>$product6,'ProductUnit'=>$product6unit7,'ProductValue'=>$productPrices[$product6]*$product6unit7),
						   $month8=>array('Product'=>$product6,'ProductUnit'=>$product6unit8,'ProductValue'=>$productPrices[$product6]*$product6unit8),
						   $month9=>array('Product'=>$product6,'ProductUnit'=>$product6unit9,'ProductValue'=>$productPrices[$product6]*$product6unit9));*/
	 }
	 //
	 $product7unit1 = isset($_REQUEST['product7unit1'])?$_REQUEST['product7unit1']:'0';
	 $product7unit2 = isset($_REQUEST['product7unit2'])?$_REQUEST['product7unit2']:'0';
	 $product7unit3 = isset($_REQUEST['product7unit3'])?$_REQUEST['product7unit3']:'0';
	 $product7unit4 = isset($_REQUEST['product7unit4'])?$_REQUEST['product7unit4']:'0';
	 $product7unit5 = isset($_REQUEST['product7unit5'])?$_REQUEST['product7unit5']:'0';
	 $product7unit6 = isset($_REQUEST['product7unit6'])?$_REQUEST['product7unit6']:'0';
	 $product7unit7 = isset($_REQUEST['product7unit7'])?$_REQUEST['product7unit7']:'0';
	 if($product7>0){
	   $productMonth[$month1][$product7] = array('ProductUnit'=>$product7unit1,'ProductValue'=>$productPrices[$product7]*$product7unit1);
	   $productMonth[$month2][$product7] = array('ProductUnit'=>$product7unit2,'ProductValue'=>$productPrices[$product7]*$product7unit2);
	   $productMonth[$month3][$product7] = array('ProductUnit'=>$product7unit3,'ProductValue'=>$productPrices[$product7]*$product7unit3);
	   $productMonth[$month4][$product7] = array('ProductUnit'=>$product7unit4,'ProductValue'=>$productPrices[$product7]*$product7unit4);
	   $productMonth[$month5][$product7] = array('ProductUnit'=>$product7unit5,'ProductValue'=>$productPrices[$product7]*$product7unit5);
	   $productMonth[$month6][$product7] = array('ProductUnit'=>$product7unit6,'ProductValue'=>$productPrices[$product7]*$product7unit6);
	   $productMonth[$month7][$product7] = array('ProductUnit'=>$product7unit7,'ProductValue'=>$productPrices[$product7]*$product7unit7);
	   $productMonth[$month8][$product7] = array('ProductUnit'=>$product7unit8,'ProductValue'=>$productPrices[$product7]*$product7unit8);
	   $productMonth[$month9][$product7] = array('ProductUnit'=>$product7unit9,'ProductValue'=>$productPrices[$product7]*$product7unit9);
	   /*$monthproductunit['product7'] = array($month1=>array('Product'=>$product7,'ProductUnit'=>$product7unit1,'ProductValue'=>$productPrices[$product7]*$product7unit1),
	   				 	   $month2=>array('Product'=>$product7,'ProductUnit'=>$product7unit2,'ProductValue'=>$productPrices[$product7]*$product7unit2),
						   $month3=>array('Product'=>$product7,'ProductUnit'=>$product7unit3,'ProductValue'=>$productPrices[$product7]*$product7unit3),
						   $month4=>array('Product'=>$product7,'ProductUnit'=>$product7unit4,'ProductValue'=>$productPrices[$product7]*$product7unit4),
						   $month5=>array('Product'=>$product7,'ProductUnit'=>$product7unit5,'ProductValue'=>$productPrices[$product7]*$product7unit5),
						   $month6=>array('Product'=>$product7,'ProductUnit'=>$product7unit6,'ProductValue'=>$productPrices[$product7]*$product7unit6),
						   $month7=>array('Product'=>$product7,'ProductUnit'=>$product7unit7,'ProductValue'=>$productPrices[$product7]*$product7unit7),
						   $month8=>array('Product'=>$product7,'ProductUnit'=>$product7unit8,'ProductValue'=>$productPrices[$product7]*$product7unit8),
						   $month9=>array('Product'=>$product7,'ProductUnit'=>$product7unit9,'ProductValue'=>$productPrices[$product7]*$product7unit9));*/
	 }
	// echo "<pre>";print_r($productMonth);die;
	 $abm = getParentLists($user_id);
	 $rbm = getParentLists($abm);
	 $zbm = getParentLists($rbm);
	 
	 //echo "<pre>"; print_r($monthproductunit);die;
     //$sql = "INSERT INTO user_detail SET address='".json_encode($_REQUEST)."'";
	 $apointmentcode = getApoinmentCode();
	 $sql = "INSERT INTO crm_appointments SET appointment_code='".$apointmentcode."',doctor_id='".$doctor_id."',expense_type='".$activitynature_id."',expense_cost='".$activity_cost."',expense_note='".$activity_detail."',be_id='".$user_id."',abm_id='".$abm."',rbm_id='".$rbm."',zbm_id='".$zbm."',total_value = '".$totalvalue."',chemist_1 ='".$chemistlist[0]['chemist_id']."',chemist_2='".$chemistlist[1]['chemist_id']."',favour='".$dd_chequedetail."',payble='".$paybleat."',created_by = '".$user_id."',created_date = NOW(),created_ip='".$_SERVER['REMOTE_ADDR']."'";
	 //echo $sql; echo "<br>";
     $execute = mysql_query($sql);
	 $apointment_id = mysql_insert_id();
	 $totalallvalue = 0;
	 foreach($productMonth as $month=>$productmonth){
	      $totalvalue = 0;
	      $sql1 = "INSERT INTO crm_appointment_potential_months SET appointment_id='".$apointment_id."',month='".$month."'"; 
			 mysql_query($sql1);
			 $potential_month_id = mysql_insert_id();
		 foreach($productmonth as $product_id=>$Unitvalues){
		  if($Unitvalues['ProductUnit']>0){
			 $sql2 = "INSERT INTO crm_appointment_potential_month_products SET potential_month_id='".$potential_month_id."',product_id='".$product_id."',unit='".$Unitvalues['ProductUnit']."',value='".$Unitvalues['ProductValue']."'"; 
			 mysql_query($sql2);
			 $totalvalue = $totalvalue + $Unitvalues['ProductValue']; 
			} 
		 }
		 $sql3 = "UPDATE crm_appointment_potential_months SET month_total_value = '".$totalvalue."' WHERE  potential_month_id='".$potential_month_id."'";
	     mysql_query($sql3);
		 $totalallvalue = $totalallvalue + $totalvalue;
	 }
	 $sql4 = "UPDATE crm_appoinments SET month_total_value = '".$totalallvalue."' WHERE  potential_month_id='".$apointment_id."'";
	 mysql_query($sql4);
	 echo json_encode(array('success'=>'YES','message'=>'CRM Added successfully'));
	 
	
	function getApoinmentCode(){
	     $sql = "SELECT appointment_code FROM crm_appoinments ORDER BY appointment_id DESC LIMIT 1";
		 $execute = mysql_query($sql);
		 $lastCode = mysql_fetch_assoc($execute);
		 return (!empty($lastCode)) ? 'APT'.(int) (substr($lastCode['appointment_code'],3)+1) : 'APT100001';
	} 
	
	function getChemist($doctor_id){
	     $sql = "SELECT * FROM crm_doctor_chemists WHERE doctor_id='".$doctor_id."'";
		 $execute = mysql_query($sql);
		 $chemits = array();
		 while($chemistids = mysql_fetch_assoc($execute)){
		   $chemits[] = $chemistids['chemist_id'];
		 }
		 return $chemits;
	}
	function getParentLists($user_id){
	  if($user_id>0){
	   $sql= "SELECT EP.parent_id FROM employee_personaldetail EP WHERE EP.user_id='".$user_id."'";
	   $execute = mysql_query($sql);
	   $parentdetail = mysql_fetch_assoc($execute);
	   return $parentdetail['parent_id'];	
	  }
	  return ''; 	 
	}
	 
?>