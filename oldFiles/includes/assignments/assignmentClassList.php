<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	getTeacherClassList($mysqli);
}
else
{
   	http_response_code(403);
}

function getTeacherClassList($mysqli)
{
	$teacherID = $_SESSION['userID'];

	?>
		<option></option>
	<?php
    	if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classTeacherID = ?"))
    	{
    		$stmt->bind_param('i', $teacherID);

	      	if ($stmt->execute())
	      	{
	     		$stmt->bind_result($classID, $className);
	        	$stmt->store_result();

	        	while ($stmt->fetch())
	        	{
	?>
		        	<option value="<?php echo $classID; ?>"><?php echo "$className"; ?></option>
	<?php
	        	}
	      	}
	      	else
	      	{
	      		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Fetching Data!',
                                  text: 'Could not execute class query.',
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