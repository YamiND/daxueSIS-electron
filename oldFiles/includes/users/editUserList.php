<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editUserList($mysqli);
}
else
{
   	http_response_code(403);
}

function editUserList($mysqli)
{
	?>
		<option></option>
	<?php
    	if ($stmt = $mysqli->prepare("SELECT userID, userEmail FROM users"))
    	{
	      	if ($stmt->execute())
	      	{
	     		$stmt->bind_result($userID, $userEmail);
	        	$stmt->store_result();

	        	while ($stmt->fetch())
	        	{
	?>
		        	<option value="<?php echo $userID; ?>"><?php echo $userEmail; ?></option>
	<?php
	        	}
	      	}
	      	else
	      	{
	      		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Fetching Data!',
                                  text: 'Could not execute user query.',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
			<?php
	      	}
	    }
	    else
	    {
	    	?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Fetching Data!',
                                  text: 'Could not prepare fetch.',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
			<?php
	    }
}

?>