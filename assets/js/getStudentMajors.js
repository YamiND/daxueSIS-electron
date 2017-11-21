function clearAndLoadSelectStudentMajors(idField)
{
    $("#" + idField).empty();
}

function getSelectStudentMajorsList(callback, idField)
{
    callback();

    var sqlite3 = require('sqlite3').verbose();
    var db = new sqlite3.Database('sqlite/daxuesis.db');

    db.serialize(function() 
    {
    
      db.each("SELECT studentMajorID, studentMajorName FROM studentMajors", 
        function(err, row) 
        {   
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
    
    var sqlite3 = require('sqlite3').verbose();
    var db = new sqlite3.Database('sqlite/daxuesis.db');

    db.serialize(function() {
    
    db.each("SELECT studentMajorID, studentMajorName FROM studentMajors", 
        function(err, row) 
        {   
            $("#" + idField).DataTable().row.add( [ row.studentMajorID, row.studentMajorName ] ).draw( false );
        });
    });

    db.close();  

}