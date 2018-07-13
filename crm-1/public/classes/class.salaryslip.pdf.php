<?php



class ClassSalaryslipPdf extends Zend_FPDF_Abstract{



	public $int_x = 1;

	public $int_y = 1;

	public $b_width = 89;

	public $b_height = 129;

	public $angle = 0;

	public $backroundExist = true;

	public $borderExist = false;

	public $_filePath = NULL;

	public $LabelFormat = NULL;

	public $outputparam = NULL;

	public $ObjDb = NULL;

	

	public function SalarySlip(){

	    $Obj = new Zend_Custom();

	    $this->AddPage();

		//$this->SetXY($this->int_x + 15,($this->int_y + $y +($inc*2.8)));

		//print_r(Bootstrap::$baseUrl.'public/admin_images/jclife.JPG');die;

		$this->Image(Bootstrap::$baseUrl.'public/admin_images/jclife.JPG',$this->int_x +70,$this->int_y+5 ,70,25,'JPG');

		

		$this->SetLineWidth(.3);

		$this->Rect($this->int_x + 10,$this->int_y +35,$this->int_x + 190,41);

		$this->SetFillColor(0,0,300);

		$this->Rect($this->int_x+10 ,$this->int_y+42,$this->int_x+190 , 7, 'F');

		

		$this->Rect($this->int_x + 10,$this->int_y +80,$this->int_x + 190,7);

		$this->SetFillColor(0,0,300);

		$this->Rect($this->int_x+10 ,$this->int_y+80,$this->int_x+190 , 7, 'F');

		

		/************************** 1ST HORIZONTAL LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+10,$this->int_y +42,$this->int_x+201,$this->int_y +42);

		

		/************************** 2ND HORIZONTAL LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+10,$this->int_y +49,$this->int_x+201,$this->int_y +49);

		

		/************************** 3RD HORIZONTAL LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+10,$this->int_y +56,$this->int_x+201,$this->int_y +56);



		/************************** 4TH HORIZONTAL LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+10,$this->int_y +63,$this->int_x+201,$this->int_y +63);

		

		/************************** 5TH HORIZONTAL LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+10,$this->int_y +70,$this->int_x+201,$this->int_y +70);

		

		/************************** 1 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+30,$this->int_y +49,$this->int_x+30,$this->int_y +76);

		

		/************************** 2 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+60,$this->int_y +42,$this->int_x+60,$this->int_y +76);

		

		/************************** 3 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+90,$this->int_y +49,$this->int_x+90,$this->int_y +76);

		

		/************************** 4 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+116,$this->int_y +70,$this->int_x+116,$this->int_y +76);

		

		/************************** 4 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+130,$this->int_y +42,$this->int_x+130,$this->int_y +70);

		

		/************************** 5 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+132,$this->int_y +70,$this->int_x+132,$this->int_y +76);

		

		/************************** 6 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+149,$this->int_y +70,$this->int_x+149,$this->int_y +76);

		

		/************************** 7 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+150,$this->int_y +35,$this->int_x+150,$this->int_y +42);

		

		/************************** 8 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+160,$this->int_y +49,$this->int_x+160,$this->int_y +70);

		

		/************************** 9 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+165,$this->int_y +70,$this->int_x+165,$this->int_y +76);

		

		/************************** 3 verticle LONG LINE ********************************/

		$this->SetLineWidth(0.3);

		$this->Line($this->int_x+182,$this->int_y +70,$this->int_x+182,$this->int_y +76);


		$this->SetFont('Helvetica','B',12);
		//echo "<pre>";print_r($this->outputparam);echo "<pre>";die;
		//$this->SetTextColor(255);

		$this->SetXY($this->int_x + 12,$this->int_y+38.5);

		$this->Cell(0,0,strtoupper($this->outputparam['UserInfo']['first_name'].' '.$this->outputparam['UserInfo']['last_name']),0,0);

		

		$this->SetFont('Helvetica','B',12);

		$this->SetTextColor(255);

		$this->SetXY($this->int_x + 12,$this->int_y+46);

		$this->Cell(0,0,ucfirst('Employee Details'),0,0);

		

		$this->SetFont('Helvetica','B',12);

		$this->SetTextColor(255);

		$this->SetXY($this->int_x + 72,$this->int_y+46);

		$this->Cell(0,0,ucfirst('Payment & Leave Details'),0,0);

		

		$this->SetFont('Helvetica','B',12);

		$this->SetTextColor(255);

		$this->SetXY($this->int_x + 134,$this->int_y+46);

		$this->Cell(0,0,ucfirst('Location Details'),0,0);

		

		$this->SetFont('Helvetica','B',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 152,$this->int_y+38.5);

		$this->Cell(0,0,strtoupper(date('F  Y',strtotime($this->outputparam['Date']))),0,0);

		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 12,$this->int_y+52.5);

		$this->Cell(0,0,'Emp no',0,0);

		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 43,$this->int_y+52.5);

		$this->CellRight(15,0,$this->outputparam['UserInfo']['employee_code'],0,0);

		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 62,$this->int_y+52.5);

		$this->Cell(15,0,'Bank Name',0,0);

		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 113,$this->int_y+52.5);

		$this->CellRight(15,0,$this->outputparam['UserInfo']['bank_name'],0,0);

		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 132,$this->int_y+52.5);

		$this->Cell(15,0,'Location',0,0);

		

		$this->SetFont('Helvetica','',11);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 161,$this->int_y+52.5);

		//$this->CellRight(15,0,$this->outputparam['Locations']['city_name'],0,0);
		$this->Cell(0,0,$this->outputparam['Locations']['city_name'],0,0);
		

//====================================================================		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 12,$this->int_y+59.5);

		$this->Cell(0,0,'Desgn.',0,0);

		

		$this->SetXY($this->int_x + 43,$this->int_y+59.5);

		$this->CellRight(15,0,$this->outputparam['UserInfo']['designation_code'],0,0);

		
      if(!empty($this->outputparam['Cheuqe'])){
			$this->SetXY($this->int_x + 62,$this->int_y+59.5);
			$this->Cell(15,0,'Cheque No.',0,0);
			$this->SetXY($this->int_x + 113,$this->int_y+59.5);
			$this->CellRight(15,0,$this->outputparam['Cheuqe']['cheque_number'],0,0);
        }else{
		  $this->SetXY($this->int_x + 62,$this->int_y+59.5);
		  $this->Cell(15,0,'Acc No.',0,0);
		  $this->SetXY($this->int_x + 113,$this->int_y+59.5);
		  $this->CellRight(15,0,$this->outputparam['UserInfo']['account_number'],0,0);
		}

		$this->SetXY($this->int_x + 132,$this->int_y+59.5);

		$this->Cell(15,0,'HQ',0,0);

		
		$this->SetFont('Helvetica','',11);
		$this->SetXY($this->int_x + 161,$this->int_y+59.5);

		//$this->CellRight(15,0,$this->outputparam['Locations']['headquater_name'],0,0);
		$this->Cell(0,0,$this->outputparam['Locations']['headquater_name'],0,0);

//======================================================================================		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 12,$this->int_y+66.5);

		$this->Cell(0,0,'Grade',0,0);

		

		$this->SetXY($this->int_x + 43,$this->int_y+66.5);

		$this->CellRight(15,0,(($this->outputparam['UserInfo']['grade']>0)?$this->outputparam['UserInfo']['grade']:''),0,0);

		

		$this->SetXY($this->int_x + 62,$this->int_y+66.5);

		$this->Cell(15,0,'Days Paid',0,0);

		

		$this->SetXY($this->int_x + 113,$this->int_y+66.5);

		$this->CellRight(15,0,number_format($this->outputparam['Paid_days'],1),0,0);

		

		$this->SetXY($this->int_x + 132,$this->int_y+66.5);

		$this->Cell(15,0,'',0,0);

		

		$this->SetXY($this->int_x + 170,$this->int_y+66.5);

		$this->CellRight(15,0,'',0,0);

		

	//=======================================================================	

		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(0);

		$this->SetXY($this->int_x + 12,$this->int_y+73.5);

		$this->Cell(0,0,'PAN',0,0);

		

		$this->SetXY($this->int_x + 43,$this->int_y+73.5);

		$this->CellRight(15,0,$this->outputparam['UserInfo']['pancard_number'],0,0);

		

		$this->SetXY($this->int_x + 62,$this->int_y+73.5);

		$this->Cell(15,0,'Leave Bal.',0,0);

		

		$this->SetXY($this->int_x + 91,$this->int_y+73.5);

		$this->Cell(15,0,'SL',0,0);
		
		
		$this->SetXY($this->int_x + 120,$this->int_y+73.5);

		$this->Cell(15,0,$this->outputparam['Leaves']['SL'],0,0);

		

		$this->SetXY($this->int_x + 134,$this->int_y+73.5);

		$this->Cell(15,0,'CL',0,0);
		
		
		$this->SetXY($this->int_x + 155,$this->int_y+73.5);

		$this->Cell(15,0,$this->outputparam['Leaves']['CL'],0,0);

		

		$this->SetXY($this->int_x + 166,$this->int_y+73.5);

		$this->Cell(15,0,'PL',0,0);
		
		$this->SetXY($this->int_x + 185,$this->int_y+73.5);

		$this->Cell(15,0,$this->outputparam['Leaves']['PL'],0,0);

//==================================================================		

		

		$this->SetFont('Helvetica','',12);

		$this->SetTextColor(255);

		$this->SetXY($this->int_x + 12,$this->int_y+83.5);

		$this->Cell(0,0,ucfirst('Earnings'),0,0);

		

		$this->SetXY($this->int_x + 72,$this->int_y+83.5);

		$this->Cell(0,0,ucfirst('Arrears (INR)'),0,0);

		

		$this->SetXY($this->int_x + 102,$this->int_y+83.5);

		$this->Cell(0,0,ucfirst('Current (INR)'),0,0);

		

		$this->SetXY($this->int_x + 133,$this->int_y+83.5);

		$this->Cell(0,0,ucfirst('Deductions'),0,0);

		

		$this->SetXY($this->int_x + 172,$this->int_y+83.5);

		$this->Cell(0,0,ucfirst('Amount (INR)'),0,0);

		

		$this->SetLineWidth(.3);

		$yinc = 7;

		foreach($this->outputparam['SalaryAmount']['Earnings'] as $key=>$salary){

		  $this->Rect($this->int_x + 10,$this->int_y +80+$yinc,$this->int_x + 120,7);

		  $this->SetFont('Helvetica','',11);

		  $this->SetTextColor(0);

		  $this->SetXY($this->int_x + 12,$this->int_y +80+$yinc+2.5);

		  if($salary['salaryhead_id']==100){

		    $this->Cell(0,0,'Loan',0,0);

		  }else{
                      $text = '';
                      if($salary['salaryhead_id']==3){
                         // $text = '('.$this->outputparam['ExpMonth'].')';
						  //$text = '(Jul & Aug)';
                      }
		  	$this->Cell(0,0,$Obj->getSalaryHeadName($salary['salaryhead_id']).$text,0,0);

		  }

		  /************************** 1 verticle LONG LINE ********************************/

		  $this->SetLineWidth(0.3);

		  $this->Line($this->int_x+70,$this->int_y +80+$yinc,$this->int_x+70,$this->int_y +80+$yinc+7);

		

		/************************** 2 verticle LONG LINE ********************************/

		  $this->SetLineWidth(0.3);

		  $this->Line($this->int_x+100,$this->int_y +80+$yinc,$this->int_x+100,$this->int_y +80+$yinc+7);

		  

		  $this->SetFont('Helvetica','',12);

		  $this->SetTextColor(0);

		  

		  $this->SetXY($this->int_x +84,$this->int_y +80+$yinc+2.5);

		  $this->CellRight(15,0,number_format($this->outputparam['SalaryAmount']['Arear'][$salary['salaryhead_id']],2),0,0);

		  

		  $this->SetXY($this->int_x +115,$this->int_y +80+$yinc+2.5);

		  $this->CellRight(15,0,number_format($salary['amount'],2),0,0);

		  $yinc =$yinc+7;

		}

		 $this->Rect($this->int_x + 10,$this->GetY()+4.5,$this->int_x + 190,7);

		 

		  $this->SetFont('Helvetica','',12);

		  $this->SetTextColor(0);

		  $this->SetXY($this->int_x + 12,$this->GetY()+8);

		  $this->Cell(0,0,'Total Earnings (Current + Arrears)',0,0); 

		  

		  $this->SetXY($this->int_x + 115,$this->GetY());
		  setlocale(LC_MONETARY, 'en_IN');
		  $this->CellRight(15,0,money_format('%!i',$this->outputparam['EarningTotal']),0,0);

		  

		  $this->SetXY($this->int_x + 133,$this->GetY());

		  $this->Cell(0,0,'Total Deductions',0,0); 

		  

		  $this->SetXY($this->int_x + 185,$this->GetY());

		  $this->CellRight(15,0,number_format($this->outputparam['DeductionTotal'],2),0,0);

		 

		 $this->Line($this->int_x+70,$this->int_y +80,$this->int_x+70,$this->GetY()-3.5);

		 $this->Line($this->int_x+100,$this->int_y +80,$this->int_x+100,$this->GetY()+3.5);

		 $this->Line($this->int_x+131,$this->int_y +80,$this->int_x+131,$this->GetY()+3.5);

		 $this->Line($this->int_x+170,$this->GetY()-3.5,$this->int_x+170,$this->GetY()+3.5);

		 $this->Line($this->int_x+201,$this->int_y +80,$this->int_x+201,$this->GetY()+3.5);

		 

		 

		 $this->Rect($this->int_x + 10,$this->GetY()+15,$this->int_x + 190,7);

		 

		 $this->SetFillColor(0,0,300);

		 $this->Rect($this->int_x+10 ,$this->GetY()+15,$this->int_x+34 , 7, 'F');

		 

		 $this->SetFont('Helvetica','B',12);

		 $this->SetTextColor(255);

		 $this->SetXY($this->int_x + 12,$this->GetY()+18.5);

		 $this->Cell(0,0,ucfirst('NET PAY (INR)'),0,0);

		 $this->SetFont('Helvetica','',12);

		 $this->SetLineWidth(0.3);

		 $this->Line($this->int_x+79,$this->GetY()-3.5,$this->int_x+79,$this->GetY()+3.5);//line before Total Amount Text

		 

		 $this->SetFont('Helvetica','',12);

		 $this->SetTextColor(0);

		 $this->SetXY($this->int_x + 58,$this->GetY()+0.5);
		 setlocale(LC_MONETARY, 'en_IN');
		 $this->CellRight(15,0,money_format('%!i',$this->outputparam['GrandTotal']),0,0);

		 
		 $this->SetFont('Helvetica','',10);
		 $this->SetXY($this->int_x + 80,$this->GetY());

		 $this->Cell(0,0,ucwords('Rs. '.str_replace(',','',$this->outputparam['TotalText'])).' Only',0,0);

		 

		  

		$yinc = 7;

		foreach($this->outputparam['SalaryAmount']['Deduction'] as $key=>$dedect){

		  $this->Rect($this->int_x + 131,$this->int_y +80+$yinc,$this->int_x + 69,7);

		  

		  $this->SetFont('Helvetica','',11);

		  $this->SetTextColor(0);

		  $this->SetXY($this->int_x + 134,$this->int_y +80+$yinc+2.5);

		   if($dedect['salaryhead_id']==100){

		    $this->Cell(0,0,'Loan',0,0);

		  }elseif($dedect['salaryhead_id']==200){

		    $this->Cell(0,0,'Provident Fund',0,0);

		  }else{

		 	$this->Cell(0,0,$Obj->getSalaryHeadName($dedect['salaryhead_id']),0,0);

		  }

//		  ************************* 1 verticle LONG LINE *******************************

		  $this->SetLineWidth(0.3);

		  $this->Line($this->int_x+170,$this->int_y +80+$yinc,$this->int_x+170,$this->int_y +80+$yinc+7);

		  

		  $this->SetFont('Helvetica','',12);

		  $this->SetTextColor(0);

		  $this->SetXY($this->int_x +185,$this->int_y +80+$yinc+2.5);

		  $this->CellRight(15,0,number_format($dedect['amount']+$this->outputparam['SalaryAmount']['Arear']['Deduct'][$dedect['salaryhead_id']],2),0,0);
			
		

		  $yinc =$yinc+7;

		}

		 $this->Line($this->int_x+170,$this->int_y +80,$this->int_x+170,$this->int_y +87);

	}

	public function printLabel(){ //print_r($this->_filePath);die;

	?>

		<script type="text/javascript">

				window.open('<?php print $this->_filePath;?>');

		</script>

	<?php

    }

	

}



?>