<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editClassList($mysqli);
}
else
{
   	http_response_code(403);
}

function editClassList($mysqli)
{
	?>
		<option></option>
	<?php
    	if ($stmt = $mysqli->prepare("SELECT classes.classID, classes.className, users.userFirstName, users.userLastName, users.userEmail FROM users INNER JOIN (classes) ON (classes.classTeacherID = users.userID) INNER JOIN (schoolYear) ON (classes.schoolYearID = schoolYear.schoolYearID AND fallSemesterStart <= CURDATE() AND springSemesterEnd >= CURDATE())"))
    	{
	      	if ($stmt->execute())
	      	{
	     		$stmt->bind_result($classID, $className, $teacherFirstName, $teacherLastName, $teacherEmail);
	        	$stmt->store_result();

	        	while ($stmt->fetch())
	        	{
	?>
		        	<option value="<?php echo $classID; ?>"><?php echo "$className - $teacherLastName, $teacherFirstName - $teacherEmail"; ?></option>
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