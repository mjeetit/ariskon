<?php
	error_reporting(E_ALL);
	$mpdf = new Zend_MPDF_mpdf('utf-8', 'A4');
	print_r($mpdf);
	echo "testing ";
?>