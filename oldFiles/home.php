<?php
  include_once 'includes/dbConnect.php';
  include_once 'includes/functions.php';

  session_start();

  // Set permission restrictions here
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Home</title>

    <?php include("includes/genericCSS.php"); ?>
    <!-- bootstrap-progressbar -->
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">

	<?php include("includes/navPanel.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
              <div class="count" id="totalUsers"></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Admins</span>
              <div class="count" id="totalAdmins"></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Teachers</span>
              <div class="count" id="totalTeachers"></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Students</span>
              <div class="count" id="totalStudents"></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-pencil-square-o"></i> Total Assignments</span>
              <div class="count" id="totalAssignments"></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-university"></i> Total Classes</span>
              <div class="count" id="totalClasses"></div>
            </div>
          </div>
          <!-- /top tiles -->

          <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2>Quick Settings</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
                    <ul class="quick-list">
                      <li><i class="fa fa-calendar-o"></i><a href="#">Settings</a>
                      </li>
                      <li><i class="fa fa-bars"></i><a href="#">Subscription</a>
                      </li>
                      <li><i class="fa fa-bar-chart"></i><a href="#">Auto Renewal</a> </li>
                      <li><i class="fa fa-line-chart"></i><a href="#">Achievements</a>
                      </li>
                      <li><i class="fa fa-bar-chart"></i><a href="#">Auto Renewal</a> </li>
                      <li><i class="fa fa-line-chart"></i><a href="#">Achievements</a>
                      </li>
                      <li><i class="fa fa-area-chart"></i><a href="#">Logout</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->

        <?php include("includes/footer.php"); ?>

      </div>
    </div>

    <?php include("includes/genericJS.php"); ?>

    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
   
    <!-- Custom Theme Scripts -->
    <script src="js/custom.min.js"></script>


    <!--Ajax Submit Form-->
    <script>
      $(document).ready(function() {  

        function getTotalUsers() {
        $.ajax({
            url:'/includes/dashboard/getTotalUsers.php',
            type:'POST',
            success:function(results) {
                $("#totalUsers").html(results);
            }
          });
        }

        function getTotalClasses() {
        $.ajax({
            url:'/includes/dashboard/getTotalClasses.php',
            type:'POST',
            success:function(results) {
                $("#totalClasses").html(results);
            }
          });
        }

        function getTotalAdmins() {
        $.ajax({
            url:'/includes/dashboard/getTotalAdmins.php',
            type:'POST',
            success:function(results) {
                $("#totalAdmins").html(results);
            }
          });
        }

        function getTotalTeachers() {
        $.ajax({
            url:'/includes/dashboard/getTotalTeachers.php',
            type:'POST',
            success:function(results) {
                $("#totalTeachers").html(results);
            }
          });
        }

        function getTotalStudents() {
        $.ajax({
            url:'/includes/dashboard/getTotalStudents.php',
            type:'POST',
            success:function(results) {
                $("#totalStudents").html(results);
            }
          });
        }

        function getTotalAssignments() {
        $.ajax({
            url:'/includes/dashboard/getTotalAssignments.php',
            type:'POST',
            success:function(results) {
                $("#totalAssignments").html(results);
            }
          });
        }
        // On load have our Classes populate
        getTotalUsers();
        getTotalAdmins();
        getTotalTeachers();
        getTotalStudents();
        getTotalAssignments();
        getTotalClasses();

      });

    </script>
  </body>
</html>
