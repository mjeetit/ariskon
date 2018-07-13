<?php
class GenerateSalary{

   public function __construct(){
      $con = mysql_connect('localhost','','');
	  mysql_select_db('jclifecare_Hrm',$con);
   }
   
   public function getGenerateSalary()
}

?>