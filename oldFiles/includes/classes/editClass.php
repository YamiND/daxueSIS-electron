<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editClassForm($mysqli);
}
else
{
   	http_response_code(403);
}

function editClassForm($mysqli)
{
	if (isset($_POST['selectedClassID'], $_POST['class-name'], $_POST['teacherID'], $_POST['class-start-time'], $_POST['class-end-time']) && !empty($_POST['selectedClassID']) && !empty($_POST['class-name']) && !empty($_POST['teacherID']) && !empty($_POST['class-start-time']) && !empty($_POST['class-end-time']))
	{
		$classID = $_POST['selectedClassID'];
		$className = $_POST['class-name'];
		$teacherID = $_POST['teacherID'];
		$classStartTime = $_POST['class-start-time'];
		$classEndTime = $_POST['class-end-time'];

		if (($classStartTime == $classEndTime) || ($classStartTime > $classEndTime))
		{
			// Time sanitization check
			?> 
			<script type="text/javascript">
			new PNotify({
	                      title: 'Error Editing Class!',
	                      text: 'Invalid Start/End Time range',
	                      type: 'error',
	                      styling: 'bootstrap3'
	                  });
			</script>
		<?php
			exit;
		}

		if ($stmt = $mysqli->prepare("SELECT schoolYearID FROM schoolYear WHERE fallSemesterStart <= CURDATE() AND springSemesterEnd >= CURDATE()"))
	    {
	    	// Get the current school year ID
	        if ($stmt->execute())
	        {
		        $stmt->bind_result($schoolYearID);
		        $stmt->store_result();

		        if ($stmt->num_rows > 0)
		        {
		           $stmt->fetch();
		        }
		        else
		        {
				?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Editing Class!',
	                                  text: 'There is no school year created for this year',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
				<?php
					exit;
		        }
		    }
		    else
		    {
		    	?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Editing Class!',
                                  text: 'Database execute failed for school year lookup',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
				<?php
				exit;
		    }
	    }
	    else
	    {
			?> 
			<script type="text/javascript">
				new PNotify({
      	                  title: 'Error Editing Class!',
                          text: 'Database prepare failed for school year lookup',
                          type: 'error',
                          styling: 'bootstrap3'
                        });
 			</script>
		<?php
			exit;
	    }

	    if ($stmt = $mysqli->prepare("SELECT classID FROM classes WHERE classStartTime = ? AND classEndTime = ? AND classTeacherID = ? AND schoolYearID = ? AND classID <> ?"))
	    {
	    	$stmt->bind_param('ssiii', $classStartTime, $classEndTime, $teacherID, $schoolYearID, $classID);

	    	if ($stmt->execute())
	    	{
	    		$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Editing Class!',
	                                  text: 'Another class with teacher assigned for selected time period',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
	<?php
					exit;
				}
	    	}
	    	else
	    	{
	    		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Editing Class!',
                                  text: 'Data could not be executed in DB',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
	<?php
				exit;
	    	}
	    }
	    else
	    {
	?> 
			<script type="text/javascript">
				new PNotify({
                              title: 'Error Editing Class!',
                              text: 'SQL Query Prepare failed for class time',
                              type: 'error',
                              styling: 'bootstrap3'
                          });
 			</script>
	<?php
			exit;
	    }

		if ($stmt = $mysqli->prepare("SELECT className FROM classes where schoolYearID = ? AND className = ? AND classID <> ?"))
		{
			// Check to see if the class already exists with the same name
			$stmt->bind_param('isi', $schoolYearID, $className, $classID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Editing Class!',
	                                  text: 'Class with name already exists.',
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
					if ($stmt = $mysqli->prepare("UPDATE classes SET className = ?, classTeacherID = ?, schoolYearID = ?, classStartTime = ?, classEndTime = ? WHERE classID = ?"))
					{
	    				$stmt->bind_param('siissi', $className, $teacherID, $schoolYearID, $classStartTime, $classEndTime, $classID);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'Class Edited',
		                                  text: 'Class has been edited',
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
			                                  title: 'Error Editing Class!',
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
			                                  title: 'Error Editing Class!',
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
			                                  title: 'Error Editing Class!',
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
                                  title: 'Error Editing Class!',
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
		?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Editing Class!',
                                  text: 'Data not sent',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
	<?php
	}	
}

?>