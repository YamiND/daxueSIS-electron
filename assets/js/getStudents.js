function clearAndLoadSelectStudents(idField)
{
    $("#" + idField).empty();
}

function getSelectStudentsList(callback, idField)
{
    callback();

    if (DEBUG)
    {
      var db = new sqlite3.Database('sqlite/daxuesis.db');
    }
    else
    {
      var db = new sqlite3.Database(path.join(app.getPath("userData"), "sqlite", "daxuesis.db"));
    }

    db.serialize(function() 
    {
      db.each("SELECT studentID, studentEnglishName FROM students", 
        function(err, row) 
        {   
            var newOption = new Option(row.studentID + " - " + row.studentEnglishName, row.studentID, false, false);
            $("#" + idField).append(newOption);
        });
    });

    db.close();
}



function clearStudentsDataTable(idField)
{
    $("#" + idField).DataTable().clear();
}

function getStudentsDataForViewTable(callback, idField)
{
    callback();
    
    if (DEBUG)
    {
        var db = new sqlite3.Database('sqlite/daxuesis.db');
    }
    else
    {
        var db = new sqlite3.Database(path.join(app.getPath("userData"), "sqlite", "daxuesis.db"));
    }

    db.serialize(function() {
    
    db.each("SELECT studentID, studentEnglishName, studentMajors.studentMajorName, studentSkillLevels.studentSkillLevel FROM students INNER JOIN studentMajors ON students.studentMajorID = studentMajors.studentMajorID INNER JOIN studentSkillLevels ON students.studentSkillLevelID = studentSkillLevels.studentSkillLevelID", 
        function(err, row) 
        {   
            $("#" + idField).DataTable().row.add( [ row.studentID, row.studentEnglishName, row.studentMajorName, row.studentSkillLevel ] ).draw( false );
        });
    });

    db.close();  

}