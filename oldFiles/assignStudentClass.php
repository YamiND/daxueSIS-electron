<template class="section-template">
    <section id="assignStudentClass" class="wrapper">

    <!-- Select2 -->
    <link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">


 
        
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2 id="classHolder">Assign Student to Class</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div id="form-content"></div>
                    <form method="post" id="assign-student-form" class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Class to add Student to</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="classID" id="classID">
                            
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="submit" class="btn btn-success">Next</button>
                        </div>
                      </div>
                    </form>

                    <form method="post" id="assign-student-form-2" class="form-horizontal form-label-left">
                      <input type="hidden" id="selectedClassID" name="selectedClassID">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="studentID">Student: <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="select2_single form-control" tabindex="-1" name="studentID" id="studentID">
                            
                          </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                          <button type="button" class="btn btn-primary" onclick="switchClass()">Switch Class</button>
                          <button type="submit" class="btn btn-success" name="edit" value="edit">Assign Student to Class</button>
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

    <!-- Select2 -->
    <script src="vendors/select2/dist/js/select2.full.min.js"></script>

    <!--Ajax Submit Form-->
    <script>
      $(document).ready(function() {  

        $("#assign-student-form-2").hide();

        // submit form using $.ajax() method
        $('#assign-student-form').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/classes/assignClassSelect.php',
            type: 'POST',
            dateType: 'JSON',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
            $("#assign-student-form").hide();

            var classInfo = JSON.parse(data);

            $('#selectedClassID').val(classInfo[0].classID);
            
            $('#classHolder').text("Class Name: " + classInfo[0].className);
            $("#assign-student-form-2").show();
            $("#studentID").select2({
              placeholder: "Select Student",
              allowClear: true
            });
            console.log(classInfo[0]);
            loadStudents(classInfo[0].classID);

          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });
    

        // submit form using $.ajax() method
        $('#assign-student-form-2').submit(function(e){
          e.preventDefault(); // Prevent Default Submission
          
          $.ajax({
            url: '/includes/classes/assignStudentClass.php',
            type: 'POST',
            data: $(this).serialize() // it will serialize the form data
          })
          .done(function(data){
              $('#form-content').fadeIn('slow').html(data);

              $("#assign-student-form-2").hide();
              $("#assign-student-form-2")[0].reset();
              $('#classHolder').text('Assign Student to Class');
              // On load have our classes populate
              loadClasses();
              $('#classID').val([]).trigger('change');
              $("#assign-student-form").show();
          })
          .fail(function(){
            alert('Ajax Submit Failed ...');  
          });
        });

        function loadClasses() {
        $.ajax({
            url:'/includes/classes/editClassList.php',
            type:'POST',
            success:function(results) {
                $("#classID").html(results);
            }
          });
        }

        function loadStudents(classID) {
        $.ajax({
            url:'/includes/classes/assignClassStudentList.php',
            type:'POST',
            data: {classID: classID},
            success:function(results) {
                $("#studentID").html(results);
                $("#studentID").val([]).trigger("change");
            }
          });
        }

        // On load have our Classes populate
        loadClasses();

      });

      function switchClass()
      {
        // Reset our form values, hide the form, and show the form
        $("#assign-student-form-2").hide();
        $("#assign-student-form-2")[0].reset();
        $('#classHolder').text('Assign Student to Class');
        $('#classID').val([]).trigger('change');
        $("#assign-student-form").show();
      }
    </script>

    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $("#classID").select2({
          placeholder: "Select Class Name",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->
  
    </section>
        </template>