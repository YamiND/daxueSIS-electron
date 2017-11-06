<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	createUserAccount($mysqli);
}
else
{
   	http_response_code(403);
}

function createUserAccount($mysqli)
{
	if (isset($_POST['first-name'], $_POST['last-name'], $_POST['email']) && !empty($_POST['first-name']) && !empty($_POST['last-name']) && !empty($_POST['email']))
	{
		$userFirstName = $_POST['first-name'];
		$userLastName = $_POST['last-name'];
		$userEmail = $_POST['email'];

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
                                  title: 'Error Creating Account!',
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
                          title: 'Error Creating Account!',
                          text: 'At least one checkbox must be checked.',
                          type: 'error',
                          styling: 'bootstrap3'
                      });
			</script>
<?php
			exit;
		}
	
		// Set the user's password to a random string
		$password = randomString();

		// Generate a random salt for the user
		$randomSalt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		
		// Hash the password with the salt
		$hashedPassword = hash("sha512", $password . $randomSalt);

		if ($stmt = $mysqli->prepare("SELECT userEmail FROM users where userEmail = ?"))
		{
			// Check to see if the email already exists
			$stmt->bind_param('s', $userEmail);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows > 0)
				{
	?> 
	   				<script type="text/javascript">
						new PNotify({
	                                  title: 'Error Creating Account!',
	                                  text: 'Account with email already exists.',
	                                  type: 'error',
	                                  styling: 'bootstrap3'
	                              });
	     			</script>
	<?php
				}
				else
				{
	    			// No other account has email set, proceed to insert
					if ($stmt = $mysqli->prepare("INSERT INTO users (userEmail, userPassword, userSalt, userFirstName, userLastName, isAdmin, isTeacher, isStudent, studentID, studentMajor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
					{
	    				$stmt->bind_param('sssssiiiss', $userEmail, $hashedPassword, $randomSalt, $userFirstName, $userLastName, $isAdmin, $isTeacher, $isStudent, $studentID, $studentMajor);

		    			if ($stmt->execute())    // Execute the prepared query.
						{
	?> 
			   				<script type="text/javascript">
		   						new PNotify({
		                                  title: 'Account Created',
		                                  text: 'Account has been created',
		                                  type: 'success',
		                                  styling: 'bootstrap3'
		                              });
		   						$('#isAdmin, #isTeacher, #isStudent').iCheck('uncheck');
              					$("#create-user-form")[0].reset();
	   						</script>
	<?php
						}
						else
						{
	?> 
			   				<script type="text/javascript">
								new PNotify({
			                                  title: 'Error Creating Account!',
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
			                                  title: 'Error Creating Account!',
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
			                                  title: 'Error Creating Account!',
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
			                                  title: 'Error Creating Account!',
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

function randomString($length = 8) 
{
	// This function is used to generate a random password
	$str = "";
	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
	$max = count($characters) - 1;
	for ($i = 0; $i < $length; $i++) 
	{
		$rand = mt_rand(0, $max);
		$str .= $characters[$rand];
	}
	return $str;
}

?>