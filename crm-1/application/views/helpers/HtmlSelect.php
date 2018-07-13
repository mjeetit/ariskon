<?php
class Zend_view_helper_HtmlSelect extends Zend_View_Helper_Abstract{

    public function htmlSelect($name,$options=array(),$select='',$properties=array()){
	   $string .= '<select name='.$name.' id='.$name.'';
	   foreach($properties as $key=>$property){
	     $string .=  ' '.$key.'='.$property.'';
	   }
	   $string .= '>';
	    
		$string .='<option value="">--Select--</option>';
	   
	   foreach($options as $key=>$option){
	     $selected = '';
		 if($key==$select){
		   $selected ='selected="selected"';
		 }
	     $string .='<option value='.$key.' '.$selected.'>'.$option.'</option>';
	   }
	   $string .= '</select>';
	   return $string;
	}
}
?>