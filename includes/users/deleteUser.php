<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	deleteUserAccount($mysqli);
}
else
{
   	http_response_code(403);
}

function deleteUserAccount($mysqli)
{
	if (isset($_POST['userID']) && !empty($_POST['userID']))
	{
		$userID = $_POST['userID'];

		if ($userID == $_SESSION['userID'])
		{
			// Make sure user can't delete their own account
			?> 
				<script type="text/javascript">
					new PNotify({
	                          title: 'Error Deleting Account!',
	                          text: 'Can not delete own account!',
	                          type: 'error',
	                          styling: 'bootstrap3'
	                      });
				</script>
	<?php
			exit;
		}

		if ($stmt = $mysqli->prepare("DELETE FROM users WHERE userID = ?"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
		?> 
   				<script type="text/javascript">
						new PNotify({
                              title: 'Account Deleted',
                              text: 'Account has been deleted',
                              type: 'success',
                              styling: 'bootstrap3'
                          });
						$('#userID').val([]).trigger('change');
						$("#userID option[value='<?php echo $userID; ?>']").remove();

					</script>

		<?php
			}
			else
			{
		?> 
				<script type="text/javascript">
					new PNotify({
	                          title: 'Error Deleting Account!',
	                          text: 'Could not delete from database',
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
                          title: 'Error Deleting Account!',
                          text: 'SQL Prepare for Deletion failed',
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
                          title: 'Error Deleting Account!',
                          text: 'Data not sent',
                          type: 'error',
                          styling: 'bootstrap3'
                      });
			</script>
	<?php
				exit;
	}
}

?>