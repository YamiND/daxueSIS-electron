<?php
include_once 'dbConnect.php';
include_once 'functions.php';
session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['userEmail'], $_POST['password'])) 
{
	// User-supplied email
    $userEmail = $_POST['userEmail'];
	
	// User-supplied password, not hased until it hits the login function
    $password = $_POST['password']; 
    if (login($userEmail, $password, $mysqli) == true)
    {
		// Go to our dashboard for the users
    	header('Location: ../home.php');
    }
    else
    {
        // Login failed, output a message via a $_SESSION variable
		$_SESSION['invalidLogin'] = '1';
		header('Location: ../login.php');
    }
}
else
{
    // The correct POST variables were not sent to this page.
    // Login failed, output a message via a $_SESSION variable
	$_SESSION['invalidLogin'] = '1';
	header('Location: ../login.php');
}