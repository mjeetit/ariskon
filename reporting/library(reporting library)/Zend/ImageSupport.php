<?php
   abstract class ImageSupport {
      public function imageValidation($file){
		 return Zend_FPDF_Image_Support::CheckImage($file);
	  }
   }
?>