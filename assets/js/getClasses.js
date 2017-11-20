function clearAndLoadClasses(idField)
{
    $("#" + idField).empty();
}

function getClassesList(callback, idField)
{
    callback();

    var sqlite3 = require('sqlite3').verbose();
    var db = new sqlite3.Database('sqlite/daxuesis.db');

    db.serialize(function() 
    {
        db.each("SELECT classID, className FROM classes", 
        function(err, row) 
        {   
            var newOption = new Option(row.className, row.classID, false, false);
            $("#" + idField).append(newOption);
        });
    });

    db.close();
}