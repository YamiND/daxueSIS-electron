<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editSchoolYearList($mysqli);
}
else
{
   	http_response_code(403);
}

function editSchoolYearList($mysqli)
{
	?>
		<option></option>
	<?php
    	if ($stmt = $mysqli->prepare("SELECT schoolYearID, fallSemesterStart, springSemesterEnd FROM schoolYear"))
    	{
	      	if ($stmt->execute())
	      	{
	     		$stmt->bind_result($schoolYearID, $fallSemesterStart, $springSemesterEnd);
	        	$stmt->store_result();

	        	while ($stmt->fetch())
	        	{
	?>
		        	<option value="<?php echo $schoolYearID; ?>"><?php echo "$fallSemesterStart - $springSemesterEnd"; ?></option>
	<?php
	        	}
	      	}
	      	else
	      	{
	      		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Fetching Data!',
                                  text: 'Could not execute school year query.',
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