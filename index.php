<?php
	
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	$root = dirname(dirname(__FILE__))."/ariskon";
	require $root.'/application/bootstrap.php';

	try{

		Bootstrap::run();

	}catch(Exception $e){
	?>

	<table width="100%">
		<tr height="500px" valign="middle">
			<td align="center"> 
				<font color="#FF0000">
					<strong><?php print $e->getMessage(); ?></strong>
				</font>
			</td>
		</tr>
	</table>	
<?php }?>

