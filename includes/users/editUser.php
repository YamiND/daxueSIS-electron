<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editUserAccount($mysqli);
}
else
{
   	http_response_code(403);
}

function editUserAccount($mysqli)
{
	if (isset($_POST['selectedUserID'], $_POST['email'], $_POST[
		'first-name'], $_POST['last-name']) && !empty($_POST['selectedUserID']) && !empty($_POST['email']) && !empty($_POST['first-name']) && !empty($_POST['last-name']))
	{
		$userID = $_POST['selectedUserID'];
		$userEmail = $_POST['email'];
		$userFirstName = $_POST['first-name'];
		$userLastName = $_POST['last-name'];

		// Set student variables to NULL
		$studentID = "";
		$studentMajor = "";

		$isAdmin = 0;
		$isTeacher = 0;
		$isStudent = 0;

		// Do our checks to make sure one of the checkboxes is checked
		if (isset($_POST['isAdmin']) || isset($_POST['isTeacher']) || isset($_POST['isStudent']))
		{
			// Make sure that student is the only box checked if they're a student
			if (isset($_POST['isStudent']) && (isset($_POST['isAdmin']) || isset($_POST['isTeacher'])))
			{
?> 
   				<script type="text/javascript">
					new PNotify({
                                  title: 'Error Editing Account!',
                                  text: 'Student checkbox must be only box checked.',
                                  type: 'error',
                                  styling: 'bootstrap3'
                              });
     			</script>
<?php
				exit;
			}
			else if (isset($_POST['isStudent']) && (!isset($_POST['isAdmin'], $_POST['isTeacher'])))
			{
				// If they are a student, make sure nothing else is set
				$isStudent = "1";	
				
				if (isset($_POST['student-id']) && !empty($_POST['student-id']))
				{
					$studentID = $_POST['student-id'];
				}
				
				if (isset($_POST['student-major']) && !empty($_POST['student-major']))
				{
					$studentMajor = $_POST['student-major'];
				}
			}
			else
			{
				// If they're not a student, let the checkbox checking commence
				if (isset($_POST['isAdmin']) && !empty($_POST['isAdmin']))
				{
					$isAdmin = "1";
				}

				if (isset($_POST['isTeacher']) && !empty($_POST['isTeacher']))
				{
					$isTeacher = "1";
				}
			}
		}
		else
		{
?> 
			<script type="text/javascript">
				new PNotify({
                          title: 'Error Editing Account!',
                          text: 'At least one checkbox must be checked.',
                          type: 'error',
                          styling: 'bootstrap3'
                      });
			</script>
<?php
			exit;
		}
	
		

		if ($stmt = $mysqli->prepare("SELECT userID FROM users where userEmail = ?"))
		{
			// Check to see if the email exists in the system under a different user
			$stmt->bind_param('s', $userEmail);

			if ($stmt->execute())
			{
				$stmt->bind_result($dbUserID);
				$stmt->store_result();

				$stmt->fetch();

				if ($stmt->num_rows > 0)
				{
					if ($dbUserID != $userID)
					{
		?> 
		   				<script type="text/javascript">
							new PNotify({
		                                  title: 'Error Editing Account!',
		                                  text: 'Account with email already exists.',
		                                  type: 'error',
		                                  styling: 'bootstrap3'
		                              });
		     			</script>
		<?php
					}
				}
				else
				{
	    			// There should be no other conflicts, prepare the update
					if ($stmt = $mysqli->prepare("UPDATE users SET userEmail = ?, userFirstName = ?, userLastName = ?, isAdmin = ?, isTeacher = ?, isStudent = ?, studentID = ?, studentMajor = ? WHERE userID = ?"))
					{
	    				$stmt->bind_param('sssiiissi', $userEmail, $userFirstName, $userLastName, $isAdmin, $isTeacher, $isStudent, $studentID, $studentMajor, $userID);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'Account Updated',
		                                  text: 'Account has been updated',
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
			                                  title: 'Error Updating Account!',
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
			                                  title: 'Error Updating Account!',
			                                  text: 'SQL Update Prepare failed',
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
			                                  title: 'Error Updating Account!',
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
			                                  title: 'Error Updating Account!',
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