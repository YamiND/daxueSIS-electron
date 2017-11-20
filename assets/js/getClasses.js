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

function clearClassesDataTable(idField)
{
    $("#" + idField).DataTable().clear();
}

function getClassesDataForViewTable(callback, idField)
{
    callback();

    var sqlite3 = require('sqlite3').verbose();
    var db = new sqlite3.Database('sqlite/daxuesis.db');

    db.serialize(function() {
    
    db.each("SELECT className, classDay, classPeriod FROM classes ORDER BY classDay ASC", 
        function(err, row) 
        {   
            var period1Start = "08:00"
            var period1End = "08:45"

            var period2Start = "8:55"
            var period2End = "09:40"
            
            var period3Start = "10:00"
            var period3End = "10:45"

            var period4Start = "10:55"
            var period4End = "11:40"

            var period5Start = "13:30"
            var period5End = "14:15"

            var period6Start = "14:25"
            var period6End = "15:10"
            
            var period7Start = "15:30"
            var period7End = "16:15"

            var period8Start = "16:25"
            var period8End = "17:10"
            
            var period9Start = "18:30"
            var period9End = "19:15"

            var period10Start = "19:25"
            var period10End = "20:10"

            var periods = [];

            var listOfPeriods = row.classPeriod;

            var hasPeriod1 = listOfPeriods & 1;
            var hasPeriod2 = listOfPeriods & 2;
            var hasPeriod3 = listOfPeriods & 4;
            var hasPeriod4 = listOfPeriods & 8;
            var hasPeriod5 = listOfPeriods & 16;
            var hasPeriod6 = listOfPeriods & 32;
            var hasPeriod7 = listOfPeriods & 64;
            var hasPeriod8 = listOfPeriods & 128;
            var hasPeriod9 = listOfPeriods & 256;
            var hasPeriod10 = listOfPeriods & 1024;

            if (hasPeriod1)
            {
                periods.push(period1Start, period1End);
            }

            if (hasPeriod2)
            {
                periods.push(period2Start, period2End);
            }

            if (hasPeriod3)
            {
                periods.push(period3Start, period3End);
            }

            if (hasPeriod4)
            {
                periods.push(period4Start, period4End);
            }

            if (hasPeriod5)
            {
                periods.push(period5Start, period5End);
            }

            if (hasPeriod6)
            {
                periods.push(period6Start, period6End);
            }

            if (hasPeriod7)
            {
                periods.push(period7Start, period7End);
            }

            if (hasPeriod8)
            {
                periods.push(period8Start, period8End);
            }

            if (hasPeriod9)
            {
                periods.push(period9Start, period9End);
            }

            if (hasPeriod10)
            {
                periods.push(period10Start, period10End);
            }

            Array.prototype.last = function() 
            {
                return this[this.length-1];
            }   

            var classStartPeriod = periods[0];
            var classEndPeriod = periods.last();

            var days = [];
            var listOfDays = row.classDay;
            
            var hasSun = listOfDays & 1;
            var hasMon = listOfDays & 2;
            var hasTue = listOfDays & 4;
            var hasWed = listOfDays & 8;
            var hasThu = listOfDays & 16;
            var hasFri = listOfDays & 32;
            var hasSat = listOfDays & 64;
            
            if (hasSun)
            {
                days.push("Sunday");
            }

            if (hasSat)
            {
                days.push("Saturday");
            }

            if (hasFri)
            {
                days.push("Friday");
            }

            if (hasThu)
            {
                days.push("Thursday");
            }

            if (hasWed)
            {
                days.push("Wednesday");
            }

            if (hasTue)
            {
                days.push("Tuesday");
            }

            if (hasMon)
            {
                days.push("Monday");
            }

            $("#" + idField).DataTable().row.add( [ row.className, days.toString(), classStartPeriod, classEndPeriod ] ).draw( false );
        });
    });

    db.close();
}