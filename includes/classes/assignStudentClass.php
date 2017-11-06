<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	assignStudentClass($mysqli);
}
else
{
   	http_response_code(403);
}

function assignStudentClass($mysqli)
{
	if (isset($_POST['selectedClassID'], $_POST['studentID']) && !empty($_POST['selectedClassID']) && !empty($_POST['studentID']))
	{
		$classID = $_POST['selectedClassID'];
		$studentID = $_POST['studentID'];

		if ($stmt = $mysqli->prepare("SELECT studentID FROM studentClassIDs WHERE classID = ? AND studentID = ?"))
		{
			// Check to see if the class already exists with the same name
			$stmt->bind_param('ii', $classID, $studentID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Assigning Student to Class!',
	                                  text: 'Student already assigned to class.',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
	<?php
				}
				else
				{
	    			// No other account has email set, proceed to insert
					if ($stmt = $mysqli->prepare("INSERT INTO studentClassIDs (studentID, classID) VALUES (?, ?)"))
					{
	    				$stmt->bind_param('ii', $studentID, $classID);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'Success Assigning Student!',
		                                  text: 'Student Assigned to Class',
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
			                                  title: 'Error Assigning Student!',
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
			                                  title: 'Error Assigning Student!',
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
                                  title: 'Error Assigning Student!',
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
                              title: 'Error Assigning Student!',
                              text: 'SQL Prepare failed',
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