<?php
  include_once 'includes/dbConnect.php';
  include_once 'includes/functions.php';

  session_start();

  // Set permission restrictions here
  if (!isAdmin($mysqli))
  {
    header("Location: /page_403.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create School Year</title>

   <?php include("includes/genericCSS.php"); ?>

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php include("includes/navPanel.php"); ?>
        <div id="form-content"></div>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Create School Year</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form method="post" id="create-schoolyear-form" class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fall-semester-start">Fall Semester Start Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" class="form-control has-feedback-left" id="fall-semester-start" name="fall-semester-start" placeholder="Fall Semester Start Date">
                          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fall-semester-end">Fall Semester End Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" class="form-control has-feedback-left" id="fall-semester-end" name="fall-semester-end" placeholder="Fall Semester End Date">
                          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="spring-semester-start">Spring Semester Start Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" class="form-control has-feedback-left" id="spring-semester-start" name="spring-semester-start" placeholder="Spring Semester Start Date">
                          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="spring-semester-end">Spring Semester End Date <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" class="form-control has-feedback-left" id="spring-semester-end" name="spring-semester-end" placeholder="Spring Semester End Date">
                          <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        </div>
                      </div>
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="submit" class="btn btn-success">Create School Year</button>
                        </div>
                      </div>
                    </form>
                    
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

    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>

    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="js/custom.min.js"></script>


    <!--Ajax Submit Form-->
    <script>
      $(document).ready(function() {  
        // submit form using $.ajax() method
        $('#create-schoolyear-form').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/schoolYear/createSchoolYear.php',
            type: 'POST',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
              $("#create-schoolyear-form")[0].reset();
              $('#form-content').fadeIn('slow').html(data);
          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });
      });

      $('#fall-semester-start').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
              },
          singleDatePicker: true,
        }, function(start) {
          console.log(start);
        });

      $('#fall-semester-end').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
              },
          singleDatePicker: true,
        }, function(start) {
          console.log(start);
        });

      $('#spring-semester-start').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
              },
          singleDatePicker: true,
        }, function(start) {
          console.log(start);
        });

      $('#spring-semester-end').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD'
              },
          singleDatePicker: true,
        }, function(start) {
          console.log(start);
        });
    </script>

    
  </body>
</html>
