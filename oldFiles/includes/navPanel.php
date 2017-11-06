<?php

if (login_check($mysqli) == true):

?>
<!--Top and Side Panel-->
  <div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="home.php" class="site_title"><i class="fa fa-graduation-cap"></i> <span>大学SIS</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="images/defaultProfile.png" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo("{$_SESSION['userFirstName']}" . " " . "{$_SESSION['userLastName']}");?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="home.php">Dashboard</a></li>
                      <li><a href="oldNavigation.php">Old Panel</a></li>
                    </ul>
                  </li>
                <?php if (isAdmin($mysqli)): ?>
                  <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="createUser.php">Create User</a></li>
                      <li><a href="editUser.php">Edit User</a></li>
                      <li><a href="deleteUser.php">Delete User</a></li>
                      <li><a href="viewUsers.php">View Users</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-university"></i> Classes <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="assignStudentClass.php">Assign Student to Class</a></li>
                      <li><a href="removeStudentClass.php">Remove Student from Class</a></li>
                      <li><a href="createClass.php">Create Class</a></li>
                      <li><a href="editClass.php">Edit Class</a></li>
                      <li><a href="deleteClass.php">Delete Class</a></li>
                      <li><a href="viewClasses.php">View Classes</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-calendar"></i> School Year <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="createSchoolYear.php">Create School Year</a></li>
                      <li><a href="editSchoolYear.php">Edit School Year</a></li>
                      <li><a href="deleteSchoolYear.php">Delete School Year</a></li>
                      <li><a href="viewSchoolYears.php">View School Years</a></li>
                    </ul>
                  </li>
                <?php endif; ?>
                <?php if (isTeacher($mysqli)): ?>
                  <li><a><i class="fa fa-pencil-square-o"></i> Assignments <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="gradeAssignment.php">Grade Assignment</a></li>
                      <li><a href="createAssignment.php">Create Assignment</a></li>
                      <li><a href="editAssignment.php">Edit Assignment</a></li>
                      <li><a href="deleteAssignment.php">Delete Assignment</a></li>
                      <li><a href="viewAssignments.php">View Assignments</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-child"></i> Students(NYD) <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="studentGrades.php">Student Grades</a></li>
                    </ul>
                  </li>
                <?php endif; ?>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings" href="settings.php">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Email" href="email.php">
                <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Refresh" onClick="window.location.reload()">
                <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="includes/logout.php">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="images/defaultProfile.png" alt=""><?php echo("{$_SESSION['userFirstName']}" . " " . "{$_SESSION['userLastName']}");?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="includes/logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
<!-- /Top and Side panel -->

<!-- jQuery custom content scroller -->
    <link href="vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
<?php

else:
  header("Location:login.php");
  #http_response_code(403);
endif;

?>
