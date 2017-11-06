<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	createAssignment($mysqli);
}
else
{
   	http_response_code(403);
}

function createAssignment($mysqli)
{
	if (isset($_POST['selectedClassID'], $_POST['assignment-name'], $_POST['assignment-points-possible']) && !empty($_POST['selectedClassID']) && !empty($_POST['assignment-name']) && !empty($_POST['assignment-points-possible']))
	{
		$classID = $_POST['selectedClassID'];
		$assignmentName = $_POST['assignment-name'];
		$assignmentPointsPossible = $_POST['assignment-points-possible'];
		
		if ($assignmentPointsPossible <= 0)        
		{
			// Time sanitization check
			?> 
			<script type="text/javascript">
			new PNotify({
	                      title: 'Error Creating Assignment!',
	                      text: 'Points can\'t be 0 or less',
	                      type: 'error',
	                      styling: 'bootstrap3'
	                  });
			</script>
		<?php
			exit;
		}

	    if ($stmt = $mysqli->prepare("SELECT assignmentID FROM assignments WHERE assignmentClassID = ? AND assignmentName = ?"))
	    {
	    	$stmt->bind_param('is', $classID, $assignmentName);

	    	if ($stmt->execute())
	    	{
	    		$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Creating Assignment!',
	                                  text: 'Another Assignment with the same name',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
	<?php
				}
				else
				{
					if ($stmt = $mysqli->prepare("INSERT INTO assignments (assignmentClassID, assignmentName, assignmentPointsPossible) VALUES (?, ?, ?)"))
					{
	    				$stmt->bind_param('isd', $classID, $assignmentName, $assignmentPointsPossible);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'Assignment Created',
		                                  text: 'Assignment has been created',
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
			                                  title: 'Error Creating Assignment!',
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
		                                  title: 'Error Creating Assignment!',
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
                                  title: 'Error Creating Assignment!',
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
                              title: 'Error Creating Assignment!',
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