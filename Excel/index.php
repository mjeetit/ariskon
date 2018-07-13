<?php
	require_once "PHPExcel.php";
	$totalRowData=array(
					0=>array(
								'Emp_Name'=>"Satish",
								'Department'=>"IT",
								'Designation'=>'Software Engineer',
								'Emp_Code'=>'SJM0096'
							),
					1=>array(
								'Emp_Name'=>"Nitesh",
								'Department'=>"IT",
								'Designation'=>'ASP.net Developer',
								'Emp_Code'=>'SJM0094'
							)
					);
	try{
			if(count($totalRowData)>0) {
			
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				$objPHPExcel = new PHPExcel();
				// Write Sheet Header
				$headers = array('0'=>'Employee Name','1'=>'Department','2'=>'Designation','3'=>'Employee Code');
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A1');

				$styleArray = array(
									'borders' => array(
									'allborders' => array(
									 'style' => PHPExcel_Style_Border::BORDER_THIN
											)
										  )
										);

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);

				unset($styleArray);
							
				// Set title row bold

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);

							

				// Setting Auto Width

				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {

				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);

				}

				// Setting Column Background Color

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

							

				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					

				$reportRows = array();
				$i==0;
				foreach($totalRowData as $row)
				{
					$reportRows[$i]['Emp_Name']=$row['Emp_Name'];
					$reportRows[$i]['Department']=$row['Department'];
					$reportRows[$i]['Designation']=$row['Designation'];
					$reportRows[$i]['Emp_Code']=$row['Emp_Code'];
					$i++;
				}
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A'.'2');

					

				// Set autofilter

				// Always include the complete filter range!

				// Excel does support setting only the caption

				// row, but that's not a best practise...

				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column

				$objPHPExcel->getActiveSheet()->setAutoFilter('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');

						

				// Rename sheet

				$objPHPExcel->getActiveSheet()->setTitle('CRM Report');

					

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);

												

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/vnd.ms-excel');

				header('Content-Disposition: attachment;filename="secondary_sales.xls"');

				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

				ob_end_clean();

				$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?

				//$objWriter->save('test.xlsx');  //THIS WORKS

				$objPHPExcel->disconnectWorksheets();

					unset($objPHPExcel);die;
			}else{
				$Header .= 	"\" No Data Found!! \"".$_nxtcol;
			}
		}catch(Exception $e){
			$_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code: '.__LINE__; 

		}		  
?>