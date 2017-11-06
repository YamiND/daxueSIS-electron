<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	createClass($mysqli);
}
else
{
   	http_response_code(403);
}

function createClass($mysqli)
{
	if (isset($_POST['class-name'], $_POST['teacherID'], $_POST['class-start-time'], $_POST['class-end-time']) && !empty($_POST['class-name']) && !empty($_POST['teacherID']) && !empty($_POST['class-start-time']) && !empty($_POST['class-end-time']))
	{
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
	                      title: 'Error Creating Class!',
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
	                                  title: 'Error Creating Class!',
	                                  text: 'There is no school year created for this year',
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
	                                  title: 'Error Creating Class!',
	                                  text: 'Database execute failed for school year lookup',
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
	                                  title: 'Error Creating Class!',
	                                  text: 'Database prepare failed for school year lookup',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
				<?php
	    }

	    if ($stmt = $mysqli->prepare("SELECT classID FROM classes WHERE classStartTime = ? AND classEndTime = ? AND classTeacherID = ? AND schoolYearID = ?"))
	    {
	    	$stmt->bind_param('ssii', $classStartTime, $classEndTime, $teacherID, $schoolYearID);

	    	if ($stmt->execute())
	    	{
	    		$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Creating Class!',
	                                  text: 'Another class with teacher assigned for selected time period',
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
			                                  title: 'Error Creating Class!',
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
                              title: 'Error Creating Class!',
                              text: 'SQL Query Prepare failed for class time',
                              type: 'error',
                              styling: 'bootstrap3'
                          });
 			</script>
	<?php
	    }

		if ($stmt = $mysqli->prepare("SELECT className FROM classes where schoolYearID = ? AND className = ?"))
		{
			// Check to see if the class already exists with the same name
			$stmt->bind_param('is', $schoolYearID, $className);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Creating Class!',
	                                  text: 'Class with name already exists.',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
	<?php
				}
				else
				{
	    			// No other account has email set, proceed to insert
					if ($stmt = $mysqli->prepare("INSERT INTO classes (className, classTeacherID, schoolYearID, classStartTime, classEndTime) VALUES (?, ?, ?, ?, ?)"))
					{
	    				$stmt->bind_param('siiss', $className, $teacherID, $schoolYearID, $classStartTime, $classEndTime);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'Class Created',
		                                  text: 'Class has been created',
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
			                                  title: 'Error Creating Class!',
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
			                                  title: 'Error Creating Class!',
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
			                                  title: 'Error Creating Class!',
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
			                                  title: 'Error Creating Class!',
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