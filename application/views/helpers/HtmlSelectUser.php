<?php
class Zend_view_helper_HtmlSelectUser extends Zend_View_Helper_Abstract{
	public function htmlSelectUser($name,$options=array(),$select='',$properties=array()){
	   if(is_array($name)){
	     $string .= '<select name='.$name[0].'[] id='.$name[0].'';
	   }else{
	      $string .= '<select name='.$name.' id='.$name.'';
	   }
	   
	   foreach($properties as $key=>$property){
	     $string .=  ' '.$key.'='.$property.'';
	   }
	   $string .= '>';
	    
		$string .='<option value="">--Select--</option>';
	   
	   foreach($options as $key=>$option){
	     $selected = '';
		 if($option['user_id']==$select){
		   $selected ='selected="selected"';
		 }
	     $string .='<option value='.$option['user_id'].' '.$selected.'>'.$option['name'].'-'.$option['employee_code'].'</option>';
	   }
	   $string .= '</select>';
	   return $string;
	}
}
?>