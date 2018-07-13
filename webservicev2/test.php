<?php
	require_once("../library/Zend/MPDF/mpdf.php");
	$html = file_get_contents('http://www.crm.jclifecare.com/Appointment/print/token/580/app/1');
	$mpdf = new Zend_MPDF_mpdf('utf-8', 'A4');
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->watermark_font = 'DejaVuSansCondensed';
	$mpdf->showWatermarkText = true;
	$mpdf->cacheTables 	   = true;
	$mpdf->simpleTables	   = true;
	$mpdf->packTableData	   = true;
	$mpdf->debug = true;

	$mpdf->WriteHTML($html);
	ob_end_clean();
	$content = $mpdf->Output('', 'S');
	file_put_contents('CRM.pdf',$content);
	require_once('class.phpmailer.php');
	$mail = new PHPMailer();
	date_default_timezone_set("Europe/Amsterdam");
	$mail->CharSet = 'utf-8';
	// START - Mail Function to Send From Server
	$mail->IsMail();
	$mail->IsHTML(true);
	$mail->MsgHTML("<p>Testing of email<p>");
	$mail->AddAttachment('CRM.pdf');
	// END - Mail Function to Send From Server
	$mail->Subject = 'Dr.:Tester-CRM Request';
	$mail->AddAddress('satishkashyap382@gmail.com','Satish Kumar Kashyap');
	//$mail->AddAddress('asanjeevsoftdot@gmail.com','Sanjeev');
	$sender ='info@jclifecare.com';
	$mail->SetFrom($sender, 'Admin');
	echo $mail->Send();
?>