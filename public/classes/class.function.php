<?php
ini_set("memory_limit", "-1");
class CommonFunction {
    public static $action = NULL;
    /**
     * Upload Csv File
     * Function : UploadFile()
     * Upload the sended file into destinaton Folder and return file name.
     * */
    public function UploadFile($folder = 'DocumentDirectory', $extension = 'xls') {
        $upload = new Zend_File_Transfer();
        $time = time();
        $Filename = Bootstrap::$root.'/public' . '/' . $folder . '/' . $time . '.' . $extension;
        $upload->addFilter('Rename', array('target' => $Filename, 'overwrite' => true));
        $upload->receive();
        return $Filename;
    }
	/**
     * Associative array
     * Function : getAssociative()
     * This Function change a array in to associative array
     * */
	 public function getAssociative($array,$key,$value){
	  $associative = array();
	  foreach($array as $data){
	    $associative[$data[$key]] = $data[$value];
	  }
	  return $associative;
   }
   /**
     * Paginator
     * Function : PageCounter()
     * This Function Generate Page counter
     * */
   public function PageCounter($total, $offset, $toshow, $class, $groupby = 10, $showcounter = 1, $linkStyle = '', $redText = 'headertext') {
	$returnTXT = "";

	if($showcounter>4){
		$showcounter=1;
	}

	if($toshow=="" || $toshow==0) {
		$toshow = 20;
	}

	if($total<($offset+$toshow)){
		$dispto=$total;
	}
	else{
		$dispto=$offset+$toshow;
	}
	if($total>0){
		if($showcounter<=2){
			$returnTXT .= "<span class=\"".$class."\">Displaying (".($offset+1)." - ".($dispto).") of ".$total."</span>&nbsp;&nbsp;";
		}
		if($total > $toshow && ($showcounter==1 || $showcounter==2 || $showcounter==3 || $showcounter==4)) {
			$no_of_pages=ceil($total/$toshow);
			if($offset != 0) {
				$returnTXT .= "<a href='".$this->url().CommonFunction::GenerateLink(($offset - $toshow), $toshow, $GLOBALS['order_by'], $GLOBALS['order_type'])."' class='".$linkStyle."'><img width='12' height='9' alt='Previous' src='http://www.hrm.jclifecare.com/public/admin_images/arrow-180-small.gif'></a>&nbsp;";
			}
			for($ii=0; $ii<ceil($total/$toshow); $ii++) {
				if(($ii+1) <= 0 ) {

				}
				else{
					if($offset<(($groupby-(floor($groupby/2)))*$toshow-1)){
						$startindex=0;
						$endindex=$groupby;
					}
					elseif(($total-$offset)<(($groupby-(floor($groupby/2)))*$toshow)){
						$totalpage=ceil($total/$toshow);
						$startindex=$totalpage-$groupby+1;
						$endindex=$totalpage;
					}
					else{
						$currentpage=ceil($offset/$toshow);
						$startindex=$currentpage-(floor($groupby/2))+2;
						$endindex=$currentpage+(floor($groupby/2))+1;
					}
					if(($ii+1)>=$startindex && ($ii+1)<=$endindex && ($showcounter==1 || $showcounter==3)){
						$link = $this->url().CommonFunction::GenerateLink(($ii*$toshow), $toshow, $GLOBALS['order_by'], $GLOBALS['order_type'])."";
						if($offset != ($ii*$toshow) ) {
							$returnTXT .= "<a href=\"".$link."\" class=\"".$linkStyle."\">".($ii+1)."</a>&nbsp;";
						}else {
							$returnTXT .= "<b><span class='".$redText."'>".($ii+1)."</b>&nbsp;";
						}
					}
				}
			}

			if(($offset + $toshow)<$total) {
				$returnTXT .= " <a href='".$this->url().CommonFunction::GenerateLink(($offset + $toshow), $toshow, $GLOBALS['order_by'], $GLOBALS['order_type'])."' class='".$linkStyle."'><img src='http://www.hrm.jclifecare.com/public/admin_images/arrow-000-small.gif' height='9' width='12' alt='Next' /></a>&nbsp;";
			}
		}
	}

	return $returnTXT;
	}

         /**
     * Geneate Display counter slect opton
     * Function : DisplayCounter()
     * This Function Create select for Display Counter
     * */
    public function DisplayCounter($total, $offset, $toshow, $class, $message = "Reports Per Page.", $start = 500, $end = 5000, $step = 100, $use = 1) {
	$returnTXT = "";

	$returnTXT .= "<font class=\"".$class."\">Show </font><select style=\"width:60px\" name=\"\"  onchange=\"window.open(this.options[this.selectedIndex].value,'_top')\" class=\"look\">";
	for ($i=$start; $i <=$end; $i=$i+$step){
		$link = $this->url().CommonFunction::GenerateLink(0, $i, $GLOBALS['order_by'], $GLOBALS['order_type'])."";
		if ($toshow == $i){
			$returnTXT .= "<option value=\"$link\" selected=\"selected\">$i</option>";
		}else{
			$returnTXT .= "<option value=\"$link\">$i</option>";
		}
	}
	$returnTXT .= "</select>&nbsp;<font class=\"".$class."\"></font>";

	return $returnTXT;
}
/**
     * Generate Link of page counter
     * Function : GenerateLink()
     * This Function Generate Link For page counter
     * */
    public function GenerateLink($offset, $toShow, $orderBy, $orderType) {
	    $linkText = '';
		if($offset != ""){
			$linkText .= "offset=".$offset."&amp;";
		}
		if($toShow != ""){
			$linkText .= "toshow=".$toShow."&amp;";
		}
		if($orderBy != ""){
			$linkText .= "order_by=".$orderBy."&amp;";
		}
		if($orderType != ""){
			$linkText .= "order_type=".$orderType."&amp;";
		}
		if(!empty($_GET)){
		   foreach($_GET as $key=>$val){
		   if($key!="offset" && $key!="toshow" && $key!="order_by" && $key!="order_type"){
		      $linkText .= $key."=".$val."&amp;";
			  }
		   }
		}
		if(substr($linkText, -5) == "&amp;"){
			$linkText = substr($linkText, 0, -5);
		}
		if($linkText != ""){
			$linkText = "?".$linkText;
		}
		return $linkText;
		}
	public function customAssociative($array){
	    $returnArr = array();
		foreach($array as $key=>$value){
		  $returnArr[$key] = $value;
		}
		return $returnArr;
	}

/**
     * Export data to csv 
     * Function : ExportCsv()
     * Write the data to csv file.
     **/
    public function ExportCsv($Csvdata, $filename = 'CSV Data',$filetype='csv') {
        header("Content-type: application/".$filetype);
        header("Content-Disposition: attachment; filename=" . $filename . ".".$filetype);
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $Csvdata;
        exit();
    }
	
	/**
     * Order By and Limit
     * Function : OdrderByAndLimit()
     * This functio return order by and Limit
     **/
	 public function OdrderByAndLimit($data,$default,$manualorder=false){
	    //Order
		 if(!empty($data['order_type']) && $data['order_type']=='DESC'){
		   $order_type = 'ASC';
		   $GLOBALS['order_type'] = 'ASC';
		 }elseif(!empty($data['order_type']) && $data['order_type']=='ASC'){
		   $order_type = 'DESC';
		   $GLOBALS['order_type'] = 'DESC';
		 }
		 else{
		   if($manualorder && $data['order_type']==''){
		     $GLOBALS['order_type'] = 'DESC';
		   }else{
		    $GLOBALS['order_type'] = 'ASC';
		   }
		   $order_type = $GLOBALS['order_type'];
		 }
		 $order_by   = (!empty($data['order_by']))?$data['order_by']:$default;
		 $GLOBALS['order_by'] = $order_by;
		 $order_type = (!empty($data['order_type']))?$data['order_type']:$GLOBALS['order_type'];
		 //Limit
		 if($GLOBALS['toshow']<=0){
		 	$GLOBALS['toshow'] = 100;
		 }	
		 $offset = (!empty($data['offset']))?$data['offset']:0;
		 $toshow = (!empty($data['toshow']))?$data['toshow']:$GLOBALS['toshow'];
		 return array('OrderBy'=>$order_by,'OrderType'=>$order_type,'Toshow'=>$toshow,'Offset'=>$offset); 
	 }	
	 /**
     * Geneate Order By Link
     * Function : OrderBy()
     * This Function Create Link For Order By
     * */
    public function OrderBy($linkname, $orderby, $class = 'toplink', $imageshow = 1) {
        $returnTXT = "";
        $returnTXT .= '<a href="' . $this->url() .CommonFunction::GenerateLink($GLOBALS['offset'],$GLOBALS['toshow'], $orderby, $GLOBALS['order_type']). '" class="' . $class . '">' . $linkname . '</a>';
      
        if ($imageshow == 1) {
            if ($GLOBALS['order_by'] == $orderby) {
                if ($GLOBALS['order_type'] == "ASC") {
                    //$GLOBALS['order_type'] = "DESC";
                   // $returnTXT .= "&nbsp;<img src=\"" . IMAGE_LINK . "/down_button.gif\" border=\"0\" alt=\"Descending\" />";
					//$returnTXT .= "&nbsp;<img src=\"" . IMAGE_LINK . "/down_button.gif\" border=\"0\" alt=\"Descending\" />";
                } elseif ($GLOBALS['order_type'] == "DESC") {
                   // $GLOBALS['order_type'] = "ASC";
                   // $returnTXT .= "&nbsp;<img src=\"" . IMAGE_LINK . "/up_button.gif\" border=\"0\" alt=\"Ascending\" />";
                }
            }
        }

        $returnTXT .= "</a>";
        return $returnTXT;
    }
}
?>