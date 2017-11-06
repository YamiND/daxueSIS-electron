<?php

include_once '../dbConnect.php';
include_once '../functions.php';

session_start(); // Our custom secure way of starting a PHP session.

if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
{
	removeClassStudent($mysqli);
}
else
{
   	http_response_code(403);
}

function removeClassStudent($mysqli)
{
  if (isset($_POST['studentID']) && !empty($_POST['studentID']))
  {
    $studentID = $_POST['studentID'];

?>
	<option></option>
	 <?php
    if ($stmt = $mysqli->prepare("DELETE FROM studentClassIDs WHERE studentID = ?"))
    {
      $stmt->bind_param('i', $studentID);

      if ($stmt->execute())
      {
       ?> 
          <script type="text/javascript">
            new PNotify({
                              title: 'Student Removed',
                              text: 'Student Removed from Class',
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