function clearAndLoadSelectStudentMajors(idField)
{
    $("#" + idField).empty(); 
}

function getSelectStudentMajorsList(callback, idField)
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
        db.each("SELECT studentMajorID, studentMajorName FROM studentMajors", function (err, row) 
        { 
            if (err)
            {
                throw err;
            }

            var newOption = new Option(row.studentMajorName, row.studentMajorID, false, false);     
            $("#" + idField).append(newOption);
        });
    });
    
    db.close();
}

function clearStudentMajorsDataTable(idField)
{
    $("#" + idField).DataTable().clear();
}

function getStudentMajorsDataForViewTable(callback, idField)
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
        db.each("SELECT studentMajorID, studentMajorName FROM studentMajors", function (err, row)
        {   
            if (err)
            {
                throw err;
            }
            else
            {
                $("#" + idField).DataTable().row.add( [ row.studentMajorID, row.studentMajorName ] ).draw( false );
            }
        });
    });
    
    db.close();  
}