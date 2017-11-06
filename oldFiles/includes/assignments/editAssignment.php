<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	editAssignment($mysqli);
}
else
{
   	http_response_code(403);
}

function editAssignment($mysqli)
{
	if (isset($_POST['selected-class-id'], $_POST['selected-assignment-id'], $_POST['assignment-name'], $_POST['assignment-points-possible']) && !empty($_POST['selected-class-id']) && !empty($_POST['selected-assignment-id']) && !empty($_POST['assignment-name']) && !empty($_POST['assignment-points-possible']))
	{
		$classID = $_POST['selected-class-id'];
		$assignmentID = $_POST['selected-assignment-id'];
		$assignmentName = $_POST['assignment-name'];
		$assignmentPointsPossible = $_POST['assignment-points-possible'];

	    if ($stmt = $mysqli->prepare("SELECT assignmentID FROM assignments WHERE assignmentName = ? AND assignmentID <> ? AND assignmentClassID = ?"))
		{
			// Check to see if the class already exists with the same name
			$stmt->bind_param('sii', $assignmentName, $assignmentID, $classID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Editing Assignment!',
	                                  text: 'Assignment with name already exists.',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
	<?php
					exit;
				}
				else
				{
	    			// No other classes has time allocated, proceed to insert
					if ($stmt = $mysqli->prepare("UPDATE assignments SET assignmentName = ?, assignmentPointsPossible = ? WHERE assignmentID = ? AND assignmentClassID = ?"))
					{
	    				$stmt->bind_param('sdii', $assignmentName, $assignmentPointsPossible, $assignmentID, $classID);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'Assignment Edited',
		                                  text: 'Assignment has been edited',
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
			                                  title: 'Error Editing Assignment!',
			                                  text: 'Data could not be updated in database',
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
			                                  title: 'Error Editing Assignment!',
			                                  text: 'SQL update Prepare failed',
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
			                                  title: 'Error Editing Assignment!',
			                                  text: 'SQL Execute failed',
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
                                  title: 'Error Editing Assignment!',
                                  text: 'SQL Prepare failed, could not error check name',
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
                                  title: 'Error Editing Assignment!',
                                  text: 'Data not sent',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
	<?php
	}	
}

?>