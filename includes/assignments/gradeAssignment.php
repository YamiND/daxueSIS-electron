<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isTeacher($mysqli)))
{
	gradeAssignment($mysqli);
}
else
{
   	http_response_code(403);
}

function gradeAssignment($mysqli)
{
	if (isset($_POST['assignmentID'], $_POST['studentID'], $_POST['assignmentPointsScored'], $_POST['classID']) && !empty($_POST['assignmentID']) && !empty($_POST['studentID']) && !empty($_POST['classID']))
	{
		$assignmentID = $_POST['assignmentID'];
		$studentID = $_POST['studentID'];

		if (empty($_POST['assignmentPointsScored']))
		{
			$assignmentPointsScored = "0";
		}
		else
		{
			$assignmentPointsScored = $_POST['assignmentPointsScored'];
		}

		$classID = $_POST['classID'];

	    if ($stmt = $mysqli->prepare("SELECT gradeID FROM grades WHERE gradeAssignmentID = ? AND gradeStudentID = ? AND gradeClassID = ?"))
		{
			// Check to see if the class already exists with the same name
			$stmt->bind_param('iii', $assignmentID, $studentID, $classID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
					if ($stmt = $mysqli->prepare("UPDATE grades SET gradeAssignmentPointsScored = ? WHERE gradeStudentID = ? AND gradeAssignmentID = ? AND gradeClassID = ?"))
					{
						$stmt->bind_param('diii', $assignmentPointsScored, $studentID, $assignmentID, $classID);

						if ($stmt->execute())
						{
							exit;
						}
						else
						{
							?> 
			   				<script type="text/javascript">
								new PNotify({
			                                  title: 'Error Editing Grades!',
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
			                                  title: 'Error Editing Grades!',
			                                  text: 'Data could not be prepared for update in database',
			                                  type: 'error',
			                                  styling: 'bootstrap3'
			                              });
			     			</script>
	<?php
					}
				}
				else
				{
	    			// No other classes has time allocated, proceed to insert
					if ($stmt = $mysqli->prepare("INSERT INTO grades (gradeClassID, gradeAssignmentID, gradeAssignmentPointsScored, gradeStudentID) VALUES (?, ?, ?, ?)"))
					{
	    				$stmt->bind_param('iidi', $classID, $assignmentID, $assignmentPointsScored, $studentID);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
							exit;
						}
						else
						{
	?> 
			   				<script type="text/javascript">
								new PNotify({
			                                  title: 'Error Editing Grades!',
			                                  text: 'Data could not be inserted in database',
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
			                                  title: 'Error Editing Grades!',
			                                  text: 'SQL insert Prepare failed',
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
			                                  title: 'Error Editing Grades!',
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
                                  title: 'Error Editing Grades!',
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
                                  title: 'Error Editing Grades!',
                                  text: 'Data not sent',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
	<?php
	}	
}

?>