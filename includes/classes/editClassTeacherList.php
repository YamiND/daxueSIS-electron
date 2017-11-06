<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	editClassTeacherList($mysqli);
}
else
{
   	http_response_code(403);
}

function editClassTeacherList($mysqli)
{
?>
	<option></option>
	 <?php
    if ($stmt = $mysqli->prepare("SELECT userID, userEmail, userFirstName, userLastName FROM users WHERE isTeacher"))
    {
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
}

?>