<?php
class Variable extends Zend_Db_Table_Abstract
{
	function setDefined()
	{
		//////////////////    Link Variables   //////////////////////

		define ("BASE_URL",Bootstrap::$baseUrl);
		define ("IMAGE_LINK",BASE_URL."public/admin_images");
		define ("CSS_LINK",BASE_URL."public/css");
		define ("JAVASCRIPT_LINK",BASE_URL."javascript");
	}
}
?>