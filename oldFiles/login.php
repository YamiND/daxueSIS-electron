<?php 

include_once 'includes/dbConnect.php';
include_once 'includes/functions.php';

session_start();

if (login_check($mysqli) == true):
  // We are already logged in, do not display page and redirect to index
  header("Location:index.php");
else:
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- Custom Theme Style -->
    <link href="css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <div class="login_wrapper">
        <div class="form login_form">
          <section class="login_content">
            <form method="POST" action="includes/processLogin.php">
            <?php 
              if (isset($_SESSION['invalidLogin'])): 
                unset($_SESSION['invalidLogin']);
            ?>
              <h1>Login Failed </h1>
            <?php else: ?>
              <h1>Login</h1>
            <?php endif; ?>
              <div>
                <input type="email" class="form-control" placeholder="Email" name="userEmail" required="" autofocus="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" name="password" required="" />
              </div>
              <div>
                <button class="btn btn-default submit" type="submit">Log in</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />

                <div>
                  <h1>大学SIS!</h1>
                  <p>©2017 All Rights Reserved. Code located at: <a href="https://github.com/YamiND/daxueSIS">Github</a></p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>

<?php 
endif;
?>
