const electron = require('electron');
// Module to control application life.
const app = electron.app;
var path = require('path');
var fs = require('fs');

const DEBUG = true;

function checkDirectorySync(directory) {  
  try {
    fs.statSync(directory);
  } catch(e) {
    fs.mkdirSync(directory);
  }
}

function createDatabase(callback)
{
  callback();

  if (!fs.existsSync(path.join(app.getPath("userData"), "sqlite", "daxuesis.db"))) 
  {
    var sqlite3 = require('sqlite3').verbose();
    let db = new sqlite3.Database(path.join(app.getPath("userData"), "sqlite", "daxuesis.db"), sqlite3.OPEN_READWRITE | sqlite3.OPEN_CREATE, function(err) {

      if (err) console.log(err.message);
      
      db.serialize(function() 
      {
        var createTableSQL = [
                              "PRAGMA foreign_keys = off;","BEGIN TRANSACTION;",
                              "DROP TABLE IF EXISTS classes;",
                              "CREATE TABLE classes (classID INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL, className VARCHAR (45) NOT NULL, classDay INT NOT NULL, classPeriod INT NOT NULL);",
                              "DROP TABLE IF EXISTS studentAttendance;",
                              "CREATE TABLE studentAttendance (attendanceID INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE, studentRefDBID INTEGER REFERENCES students (studentDBID) ON DELETE CASCADE, classRefID INTEGER REFERENCES classes (classID) ON DELETE CASCADE, attendanceDate DATE, attendedClass BOOLEAN);",
                              "DROP TABLE IF EXISTS studentClasses;",
                              "CREATE TABLE studentClasses (studentRefID INT REFERENCES students (studentID) ON DELETE CASCADE, classRefID INTEGER REFERENCES classes (classID) ON DELETE CASCADE);",
                              "DROP TABLE IF EXISTS studentMajors;",
                              "CREATE TABLE studentMajors (studentMajorID INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE, studentMajorName STRING NOT NULL UNIQUE);",
                              "DROP TABLE IF EXISTS students;",
                              "CREATE TABLE students (studentDBID INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL, studentEnglishName  VARCHAR (45), studentMajorID INTEGER REFERENCES studentMajors (studentMajorID) ON DELETE CASCADE, studentSkillLevelID INT NOT NULL REFERENCES studentSkillLevels (studentSkillLevelID) ON DELETE CASCADE, studentID INTEGER UNIQUE NOT NULL);",
                              "DROP TABLE IF EXISTS studentSkillLevels;",
                              "CREATE TABLE studentSkillLevels (studentSkillLevelID INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL, studentSkillLevel INTEGER UNIQUE NOT NULL);",
                              "DROP TABLE IF EXISTS user;",
                              "CREATE TABLE user (userID INT UNIQUE PRIMARY KEY NOT NULL, userFirstName STRING NOT NULL, userLastName STRING NOT NULL, userPassword STRING);", 
                              "COMMIT TRANSACTION;",
                              "PRAGMA foreign_keys = on;"
                            ];

        for(var i=0; i< createTableSQL.length; i++)
        {
          console.log("execute sql : " + createTableSQL[i]);
          db.run(createTableSQL[i], function(error) 
          {
            if (error) 
            {
              throw error;
            }
          });
        }                       
      }); 

      db.close();
    });
  }
}

// Module to create native browser window.
const BrowserWindow = electron.BrowserWindow
//Adds the main Menu to our app

// Keep a global reference of the window object, if you don't, the window will
// be closed automatically when the JavaScript object is garbage collected.
let mainWindow

function createWindow () {
  // Create the browser window.
    // In the future I might use a frameless browser again
    //mainWindow = new BrowserWindow({titleBarStyle: 'hidden',
    mainWindow = new BrowserWindow({
    width: 1281,
    height: 800,
    minWidth: 1281,
    minHeight: 800,
    show: false,
    icon: path.join(__dirname, 'assets/icons/daxuesis.icns')
  })

  // and load the index.html of the app.
  mainWindow.loadURL(`file://${__dirname}/index.html`)
  // Open the DevTools.
  mainWindow.webContents.openDevTools()


  // Show the mainwindow when it is loaded and ready to show
  mainWindow.once('ready-to-show', () => {
    mainWindow.show()
  })

  // Emitted when the window is closed.
  mainWindow.on('closed', function () {
    // Dereference the window object, usually you would store windows
    // in an array if your app supports multi windows, this is the time
    // when you should delete the corresponding element.
    mainWindow = null
  })

  require('./menu/mainmenu')
  
  if (!DEBUG)
  {
    createDatabase(function () {checkDirectorySync(path.join(app.getPath("userData"), "sqlite"))}); // Check if sqlite dir exists 
  }
}

// This method will be called when Electron has finished
// initialization and is ready to create browser windows.
// Some APIs can only be used after this event occurs.
app.on('ready', createWindow)

// Quit when all windows are closed.
app.on('window-all-closed', function () {
  // On OS X it is common for applications and their menu bar
  // to stay active until the user quits explicitly with Cmd + Q
  if (process.platform !== 'darwin') {
    app.quit()
  }
})

app.on('activate', function () {
  // On OS X it's common to re-create a window in the app when the
  // dock icon is clicked and there are no other windows open.
  if (mainWindow === null) {
    createWindow()
  }
})

// In this file you can include the rest of your app's specific main process
// code. You can also put them in separate files and require them here.
