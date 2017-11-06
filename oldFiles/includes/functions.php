<?php
include_once 'dbConfig.php';

function login($userEmail, $password, $mysqli) 
{
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT userID, userFirstName, userLastName, userPassword, userSalt FROM users WHERE userEmail = ? LIMIT 1")) 
    {
        $stmt->bind_param('s', $userEmail);  // Bind "$email" to parameter.

        if ($stmt->execute())
        {   
        	// Execute the prepared query.
	        $stmt->store_result();
	 
	        // get variables from result.
	        $stmt->bind_result($userID, $userFirstName, $userLastName, $dbPassword, $userSalt);

	        if ($stmt->num_rows == 1) 
			{
	        	$stmt->fetch();
		
				$tempPassword = $password;  // This value is the hashed passwodr passed in
		        // hash the password with the unique salt.
		        $password = hash('sha512', $password . $userSalt);
                if ($dbPassword == $password) 
                {
                    // Password is correct!

                    $_SESSION['userID'] = $userID;
                    $_SESSION['userFirstName'] = $userFirstName;
                    $_SESSION['userLastName'] = $userLastName;
		    		$_SESSION['userEmail'] = $userEmail;
                    $_SESSION['login_string'] = hash('sha512', $dbPassword);
		
                    // Login successful.
                    return true;
                } 
                else 
                {
                    return false;
                } 
	        } 
	        else 
	        {
	            // No user exists.
	            return false;
	        }
	    }
	}
}

function login_check($mysqli) 
{
    // Check if all session variables are set 
    if (isset($_SESSION['userID'], $_SESSION['login_string'])) 
    {
        $userID = $_SESSION['userID'];
        $login_string = $_SESSION['login_string'];

        if ($stmt = $mysqli->prepare("SELECT userPassword FROM users WHERE userID = ? LIMIT 1")) 
		{
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $userID);
            if ($stmt->execute())   // Execute the prepared query.
            {
            	$stmt->store_result();
 
	            if ($stmt->num_rows == 1) 
		    	{
	                // If the user exists get variables from result.
	                $stmt->bind_result($password);
	                $stmt->fetch();
	                $login_check = hash('sha512', $password);
			
	                if ($login_check == $login_string) 
					{
	                    // Logged In!!!! 
	                    return true;
	                } 
					else
					{
	                	// Not logged in 
	            		return false;
	            	}
	            } 
		    	else 
		    	{
					// Not logged in 
	                return false;
	       		}
	       	}
	       	else
	       	{
	       		return false;
	       	}
        } 
		else 
		{
            // Not logged in 
            return false;
        }
    } 
    else 
    {
        // Not logged in 
        return false;
    }
}

function isAdmin($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];

		if ($stmt = $mysqli->prepare("SELECT isAdmin FROM users WHERE userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows == 1)
				{
					$stmt->bind_result($isAdmin);

					$stmt->fetch();

					return $isAdmin;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function isTeacher($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];

		if ($stmt = $mysqli->prepare("SELECT isTeacher FROM users WHERE userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows == 1)
				{
					$stmt->bind_result($isTeacher);

					$stmt->fetch();

					return $isTeacher;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function isStudent($mysqli)
{
	if (isset($_SESSION['userID']) && !empty($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];

		if ($stmt = $mysqli->prepare("SELECT isStudent FROM users WHERE userID = ? LIMIT 1"))
		{
			$stmt->bind_param('i', $userID);

			if ($stmt->execute())
			{
				$stmt->store_result();

				if ($stmt->num_rows == 1)
				{
					$stmt->bind_result($isStudent);

					$stmt->fetch();

					return $isStudent;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

?>