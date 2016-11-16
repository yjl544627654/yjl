<?php
	if($_POST['act']=="remove"){
		echo $_POST['id']."  ".$_POST['node_index'];
	}elseif($_POST['act']=="setname"){
		echo $_POST['text'];
	}elseif($_POST['act']=="add"){
		echo $_POST['add_id'];
	}
	

?>