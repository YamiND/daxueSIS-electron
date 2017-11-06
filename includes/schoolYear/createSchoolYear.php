<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	createSchoolYear($mysqli);
}
else
{
   	http_response_code(403);
}

function createSchoolYear($mysqli)
{
	if (isset($_POST['fall-semester-start'], $_POST['fall-semester-end'], $_POST['spring-semester-start'], $_POST['spring-semester-end']) && !empty($_POST['fall-semester-start']) && !empty($_POST['fall-semester-end']) && !empty($_POST['spring-semester-start']) && !empty($_POST['spring-semester-end']))
	{
		$fallSemesterStart = $_POST['fall-semester-start'];
		$fallSemesterEnd = $_POST['fall-semester-end'];
		$springSemesterStart = $_POST['spring-semester-start'];
		$springSemesterEnd = $_POST['spring-semester-end'];
		

		if (($fallSemesterStart === $fallSemesterEnd && $fallSemesterEnd === $springSemesterStart && $springSemesterStart === $springSemesterEnd) || ($fallSemesterStart > $fallSemesterEnd) || ($springSemesterStart > $springSemesterEnd) || ($fallSemesterStart > $springSemesterStart) || ($fallSemesterStart > $springSemesterEnd))        
		{
			// Time sanitization check
			?> 
			<script type="text/javascript">
			new PNotify({
	                      title: 'Error Creating School Year!',
	                      text: 'Invalid Start/End Date range',
	                      type: 'error',
	                      styling: 'bootstrap3'
	                  });
			</script>
		<?php
			exit;
		}

	    if ($stmt = $mysqli->prepare("SELECT schoolYearID FROM schoolYear WHERE fallSemesterStart = ? OR fallSemesterEnd = ? OR springSemesterStart = ? OR springSemesterEnd = ?"))
	    {
	    	$stmt->bind_param('ssss', $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd);

	    	if ($stmt->execute())
	    	{
	    		$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Creating School Year!',
	                                  text: 'Another School Year with the selected time period',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
	<?php
				}
				else
				{
					if ($stmt = $mysqli->prepare("INSERT INTO schoolYear (fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd) VALUES (?, ?, ?, ?)"))
					{
	    				$stmt->bind_param('ssss', $fallSemesterStart, $fallSemesterEnd, $springSemesterStart, $springSemesterEnd);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'School Year Created',
		                                  text: 'School Year has been created',
		                                  type: 'success',
		                                  styling: 'bootstrap3'
		                              });
	   						</script>
	<?php
						}
						else
						{
	?> 
			   				<script type="text/javascript">
								new PNotify({
			                                  title: 'Error Creating School Year!',
			                                  text: 'Data could not be inserted into database',
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
		                                  title: 'Error Creating School Year!',
		                                  text: 'SQL Insert Prepare failed',
		                                  type: 'error',
		                                  styling: 'bootstrap3'
		                              });
		     			</script>
	<?php
					}
				}
	    	}
	    	else
	    	{
	    		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Creating School Year!',
                                  text: 'Data could not be executed in DB',
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
                              title: 'Error Creating School Year!',
                              text: 'SQL Query Prepare failed for date range',
                              type: 'error',
                              styling: 'bootstrap3'
                          });
 			</script>
	<?php
	    }
	}
	else
	{
   		http_response_code(403);
	}
}

?>