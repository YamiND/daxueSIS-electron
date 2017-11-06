<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	assignClassStudentList($mysqli);
}
else
{
   	http_response_code(403);
}

function assignClassStudentList($mysqli)
{
  if (isset($_POST['classID']) && !empty($_POST['classID']))
  {
    $classID = $_POST['classID'];

?>
	<option></option>
	 <?php
    if ($stmt = $mysqli->prepare("SELECT userID, userEmail, userFirstName, userLastName FROM users WHERE isStudent AND userID NOT IN (SELECT studentID FROM studentClassIDs WHERE classID LIKE ?)"))
    {
      $stmt->bind_param('i', $classID);
      if ($stmt->execute())
      {
        $stmt->bind_result($userID, $userEmail, $userFirstName, $userLastName);
        $stmt->store_result();

        while ($stmt->fetch())
        {
  ?>
          <option value="<?php echo $userID; ?>"><?php echo "$userEmail - $userLastName, $userFirstName"; ?></option>
  <?php
        }
      }
      else
      {
      	?> 
			<script type="text/javascript">
			new PNotify({
                          title: 'Error Fetching Data!',
                          text: 'Could not execute class query.',
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
                          title: 'Error Fetching Data!',
                          text: 'SQL Prepare Failed.',
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
                          title: 'Error Fetching Data!',
                          text: 'Data not sent.',
                          type: 'error',
                          styling: 'bootstrap3'
                      });
      </script>
    <?php
  }
}

?>