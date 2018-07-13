<?php
	//require_once "PHPExcel.php";
	$filename="public/BHAGALPUR-2.xlsx";
	//error_reporting(E_ALL); 
	$inputFileType = PHPExcel_IOFactory::identify($filename);
	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	$objReader->setReadDataOnly(true);
	$excelObj = $objReader->load($filename);
	
	$worksheet=$excelObj->getActiveSheet();
	$lastRow=$worksheet->getHighestRow();
	$lastColumn=$worksheet->getHighestColumn();
	$letters = range('A',$lastColumn);
	$dbHost = 'localhost';
	$dbUsername = 'jclifesc_hrmERP';
	$dbPassword = 'h[)RNx~0W*DQ';
	$dbName = 'jclifesc_hrmERP';
	//connect with the database
	$con = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
	$doctors=array();
	echo "<table border='3'>";
	echo "<tr><th>Business unit</th><th>Country</th><th>Zone</th><th>Region</th><th>Area</th><th>Headquarter</th><th>City</th><th>Patch</th><th>Name</th></tr>";
	for($row1=2;$row1<=121;$row1++){
		$doctor = trim($worksheet->getCell("M".$row1)->getValue());
		
			$sql="SELECT * FROM `crm_doctors` WHERE `doctor_name` LIKE '".$doctor."'";
			$result=mysqli_query($con,$sql);
			$row=mysqli_fetch_assoc($result);
			echo "<tr>";
			$b_unit=trim($worksheet->getCell("A".$row1)->getValue());
			$country=trim($worksheet->getCell("B".$row1)->getValue());
			$zone=trim($worksheet->getCell("C".$row1)->getValue());
			$region=trim($worksheet->getCell("D".$row1)->getValue());
			$area=trim($worksheet->getCell("E".$row1)->getValue());
			$hq=trim($worksheet->getCell("F".$row1)->getValue());
			$city=trim($worksheet->getCell("G".$row1)->getValue());
			$patch=trim($worksheet->getCell("H".$row1)->getValue());
			$speciality=trim($worksheet->getCell("N".$row1)->getValue());
			$qualification=trim($worksheet->getCell("O".$row1)->getValue());
			
			if(!empty($row)){
				$queryBuilder="";
				echo "<td>$row[business_unit_id]-$b_unit</td>";
				echo "<td>$row[country_id]-$country</td>";
				
				$temp=($row['zone_id']==$zone)?1:0;
				if($temp){
					echo "<td style='color:green;font-weight:bold'>True</td>";
				}else{
					echo "<td style='color:red;font-weight:bold'>False</td>";
					$queryBuilder="zone_id='".$zone."',";
				}
				
				$temp=($row['region_id']==$region)?1:0;
				if($temp){
					echo "<td style='color:green;font-weight:bold'>True</td>";
				}else{
					echo "<td style='color:red;font-weight:bold'>False</td>";
					$queryBuilder.="region_id='".$region."',";
				}
				
				$temp=($row['area_id']==$area)?1:0;
				if($temp){
					echo "<td style='color:green;font-weight:bold'>True</td>";
				}else{
					echo "<td style='color:red;font-weight:bold'>False</td>";
					$queryBuilder.="area_id='".$area."',";
				}
				
				$temp=($row['headquater_id']==$hq)?1:0;
				if($temp){
					echo "<td style='color:green;font-weight:bold'>True</td>";
				}else{
					echo "<td style='color:red;font-weight:bold'>False</td>";
					$queryBuilder.="headquater_id='".$hq."',";
				}
				
				$temp=($row['city_id']==$city)?1:0;
				if($temp){
					echo "<td style='color:green;font-weight:bold'>True</td>";
				}else{
					echo "<td style='color:red;font-weight:bold'>False</td>";
					$queryBuilder.="city_id='".$city."',";
				}
				
				$temp=($row['patch_id']==$patch)?1:0;
				if($temp){
					echo "<td style='color:green;font-weight:bold'>True</td>";
				}else{
					echo "<td style='color:red;font-weight:bold'>False</td>";
					$queryBuilder.="patch_id='".$patch."',";
				}
				
				//echo "<td>$row[business_unit_id]-$b_unit</td>";
				//echo "<td>$row[country_id]-$country</td>";
				//echo "<td>$row[zone_id]-$zone</td>";
				//echo "<td>$row[region_id]-$region</td>";
				//echo "<td>$row[area_id]-$area</td>";
				//echo "<td>$row[headquater_id]-$hq</td>";
				//echo "<td>$row[city_id]-$city</td>";
				//echo "<td>$row[patch_id]-$patch</td>";
				echo "<td>$doctor</td></tr>";
				/*if($queryBuilder){
					$queryBuilder=substr($queryBuilder, 0, -1);
					$query="UPDATE crm_doctors SET $queryBuilder  WHERE doctor_id='$row[doctor_id]'";
					//mysqli_query($con,$query);
					echo $query."<br/>";
				}*/
			}else{
				$query="SELECT * FROM `crm_doctors` ORDER BY `doctor_id` DESC LIMIT 1";
				$result=mysqli_query($con,$query);
				$row=mysqli_fetch_assoc($result);
				$code=substr($row['doctor_code'],2);
				$code++;
				$requestData['doctor_code']="DR".$code;
				$requestData['org_svl_number']=$row['org_svl_number']+1;
				$requestData['svl_number']=$row['svl_number']+1;
				$requestData['doctor_name']=$doctor;
				$requestData['user_id']=1;
				
				$query="INSERT INTO crm_doctors SET 
	   				 doctor_code='".$requestData['doctor_code']."',	
					 org_svl_number='".$requestData['org_svl_number']."',
					 svl_number='".$requestData['svl_number']."',
					 patch_id='".$patch."',
					 city_id='".$city."',
					 area_id='".$area."',
					 region_id='".$region."',
					 zone_id='".$zone."',
					 country_id='".$country."',
					 business_unit_id='".$b_unit."',
					 headquater_id='".$hq."',
					 doctor_name='".$requestData['doctor_name']."',
					 speciality='".$speciality."',
					 qualification='".$qualification."',
					 create_type=3,
					 isActive=1,
					 created_date='".date('Y-m-d h:i:s',time())."',
					 created_ip='".$_SERVER['REMOTE_ADDR']."',
					 isApproved=1,
					 approved_by='".$requestData['user_id']."',
					 approved_date='".date('Y-m-d H:i:s',time())."',
					 approved_ip='".$_SERVER['REMOTE_ADDR']."',
					 created_by='".$requestData['user_id']."'";
					 echo $query;
					// mysqli_query($con,$query);
			}
	}
	echo "</table>";
	/*$query="INSERT INTO crm_doctors SET 
	   				 doctor_code='".date('ymdhi').$requestData['patch_id']."',	
					 patch_id='".$requestData['patch_id']."',
					 city_id='".$patchDetails['city_id']."',
					 area_id='".$patchDetails['area_id']."',
					 region_id='".$patchDetails['region_id']."',
					 zone_id='".$patchDetails['zone_id']."',
					 country_id='".$patchDetails['country_id']."',
					 business_unit_id='".$patchDetails['bunit_id']."',
					 headquater_id='".$patchDetails['headquater_id']."',
					 doctor_name='".$requestData['doctor_name']."',
					 create_type=3,
					 created_by='".$requestData['user_id']."'";
	echo $query;	*/			
	//$this->query();
	//print_r($doctors);die;
?>
