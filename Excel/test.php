<?php
	error_reporting(E_ALL);
	require_once "PHPExcel.php";
	$tmpfname="KAMLESH.xls";
	
	$excelReader=PHPExcel_IOFactory::createReaderForFile($tmpfname);
	$excelObj=$excelReader->load($tmpfname);echo "Satish";die;
	
	$worksheet=$excelObj->getActiveSheet();
	$lastRow=$worksheet->getHighestRow();
	$lastColumn=$worksheet->getHighestColumn();
	$letters = range('A',$lastColumn);

	$dbHost = 'localhost';
	$dbUsername = 'root';
	$dbPassword = '';
	$dbName = 'hrm';
	//connect with the database
	$con = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);
	//get search term
	//get matched data from skills table
	for($row1=9;$row1<=$lastRow;$row1++){
		foreach ($letters as $alpha) {
			if($alpha=='A'){
				$date=trim($worksheet->getCell($alpha.$row1)->getValue());
				
			}elseif($alpha=='E'){
				$hq=trim($worksheet->getCell($alpha.$row1)->getValue());
				$sql="SELECT headquater_id FROM headquater WHERE headquater_name ='".$hq."'";
				$result=mysqli_query($con,$sql);
				$row=mysqli_fetch_row($result);
				$HQid=$row[0];
			}elseif($alpha=='D'){
				
				$patch=trim($worksheet->getCell($alpha.$row1)->getValue());
				$sql="SELECT patch_id FROM patchcodes WHERE patch_name ='".$patch."'";
				$result=mysqli_query($con,$sql);
				$row=mysqli_fetch_row($result);
				$locationid=$row[0];
			}elseif($alpha=='F'){
				
				$workwith=trim($worksheet->getCell($alpha.$row1)->getValue());
				$sql="SELECT * FROM employee_personaldetail WHERE CONCAT(first_name,' ',last_name)='".$workwith."'";
				$result=mysqli_query($con,$sql);
				
				if(!empty($result)){
					
					$row=mysqli_fetch_assoc($result);
					
					if($row['designation_id']==8){
						$beid=$row['user_id'];
						$abmid='';
						$rbmid='';
					}elseif($row['designation_id']==7){
						$beid='';
						$abmid=$row['user_id'];
						$rbmid='';
					}elseif($row['designation_id']==6){
						$beid='';
						$abmid='';
						$rbmid=$row['user_id'];
					}
					
				}else{
					$beid='';
					$abmid='';
					$rbmid='';
				}
			}
			echo "date=".$date." , HQid=".$HQid." , locationid=".$locationid." , beid=".$beid." , abmid=".$abmid." , rbmid=".$rbmid;die;
		}
	}
?>
