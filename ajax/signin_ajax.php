<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require '../includes/config.php';
    require '../includes/functions.php';
    require '../includes/flatfile.php';

    $db = new Flatfile();
    $db->datadir = '../data/';

    /*
     * Initialize the process
     * This switch decides what task is to be performed based on the requestType
     */
    $mode = $_POST['requestType'];
    switch ($mode) {
        case 'signin':signin($db, $_POST['username'], $_POST['password']); break;
        case 'signup':signup($db, $siteName, $siteUrl, $siteEmail, $_POST['username'], $_POST['useremail'], $_POST['password']); break;
        case 'resetpass':resetpass($db, $siteName, $siteUrl, $siteEmail, $_POST['useremail']); break;
        case 'usercheck':usercheck($db, $_POST['username']); break;
        case 'emailcheck':emailcheck($db, $_POST['useremail']); break;
    }

    /*
     * Function to Sign a user in
     *
     * @param string		$db			The Flat File Connction
     * @param string		$username	The User's Account username passed via the sign in form
     * @param string		$password	The User's Account password passed via the sign in form
     *
     * @return array					The User's basic Account info
     */
    function signin($db, $username, $password)
    {
        define('USER_ID', 0);
        define('USERNAME', 1);
        define('PASSWORD', 2);
        define('USER_EMAIL', 3);
        define('DATE_CREATED', 4);

        $usrname = htmlspecialchars($username);
        $pass = encodeIt($password);

        $compClause = new AndWhereClause();
        $compClause->add(new SimpleWhereClause(USERNAME, '=', $usrname, STRING_COMPARISON));
        $compClause->add(new SimpleWhereClause(PASSWORD, '=', $pass, STRING_COMPARISON));
        $userdata = $db->selectWhere('users.txt', $compClause, 1);

        foreach ($userdata as $item => $row) {
            $_SESSION['st']['userId'] = $row[0];
            $_SESSION['st']['userName'] = $row[1];
            $_SESSION['st']['userEmail'] = $row[3];
        }

        echo json_encode($userdata);
    }

    /*
     * Function to create a New User Account
     *
     * @param string		$db			The Flat File Connction
     * @param string		$username	The desired Account username passed via the sign in form
     * @param string		$useremail	The User's Email Address passed via the sign in form
     * @param string		$password	The desired Account password passed via the sign in form
     *
     * @return boolean
     */
    function signup($db, $siteName, $siteUrl, $siteEmail, $username, $useremail, $password)
    {
        define('USER_ID', 0);
        define('USERNAME', 1);
        define('PASSWORD', 2);
        define('USER_EMAIL', 3);
        define('DATE_CREATED', 4);

        // Get $_POST data
        $usrname = htmlspecialchars(alphaNum($username));
        $usremail = htmlspecialchars($useremail);
        $pass = encodeIt($password);
        $dateCreated = date('Y-m-d H:i:s');

        // Generate a RANDOM Hash
        $randomHash = uniqid(rand());
        // Take the first 8 hash digits and use it as the User's ID
        $randHash = substr($randomHash, 0, 8);

        // Set the values to insert
        $newuser[USER_ID] = $randHash;
        $newuser[USERNAME] = $usrname;
        $newuser[PASSWORD] = $pass;
        $newuser[USER_EMAIL] = $usremail;
        $newuser[DATE_CREATED] = $dateCreated;

        $new_user = $db->insert(
            'users.txt',
            $newuser
        );

        $userinfo[USER_ID] = $randHash;
        $userinfo[USERNAME] = $usrname;
        $userinfo[DATE_CREATED] = $dateCreated;

        // Define the File to be created
        $userFile = $usrname.'-'.$randHash;

        // Insert the data
        $new_user = $db->insert(
            $userFile.'.txt',
            $userinfo
        );

        // Send out Notification Email to the New User
        $subject = $siteName.' New Account Created';

        $message = '<html><body>';
        $message .= '<h3>'.$subject.'</h3>';
        $message .= '<p>';
        $message .= 'Your new account has been successfully created, and you can now sign in.<br />';
        $message .= '<a href="'.$siteUrl.'sign-in.php">Sign In</a>';
        $message .= '</p>';
        $message .= '<p>Username: '.$usrname.'<br />Password: The password you signed up with.</p>';
        $message .= '<hr />';
        $message .= '<p>Thank You,<br>'.$siteName.'</p>';
        $message .= '</body></html>';

        $headers = 'From: '.$siteName.' <'.$siteEmail.">\r\n";
        $headers .= 'Reply-To: '.$siteEmail."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        mail($usremail, $subject, $message, $headers);

        // Check if the file was created
        $checkFile = '../data/'.$userFile.'.txt';

        if (file_exists($checkFile)) {
            echo '1';    // All is good!
        } else {
            echo '0';    // Nope, error...
        }
    }

    /*
     * Function to reset a User's Account Password
     *
     * @param string		$db			The Flat File Connction
     * @param string		$useremail	The User's Email Address passed via the sign in form
     *
     * @return boolean					1 = Account password for the specific account has been reset
     */
    function resetpass($db, $siteName, $siteUrl, $siteEmail, $useremail)
    {
        $userdata = '';

        define('USER_ID', 0);
        define('USERNAME', 1);
        define('PASSWORD', 2);
        define('USER_EMAIL', 3);
        define('DATE_CREATED', 4);

        $useremail = htmlspecialchars($useremail);

        $userdata = $db->selectWhere(
            'users.txt',
            new SimpleWhereClause(USER_EMAIL, '=', $useremail)
        );

        if (empty($userdata)) {
            echo '0';    // No User found
        } else {
            global $uid;
            global $uname;
            foreach ($userdata as $item => $row) {
                $uid = $row[0];
                $uname = $row[1];
            }

            $userfile = $db->selectAll($uname.'-'.$uid.'.txt');

            // Generate a RANDOM Hash
            $randomHash = uniqid(rand());
            // Take the first 8 hash digits and use it as the User's new password
            $randHash = substr($randomHash, 0, 8);

            $newpass = encodeIt($randHash);

            $db->updateSetWhere(
                'users.txt', [
                    PASSWORD => $newpass,
                ],
                new SimpleWhereClause(
                    USER_ID, '=', $uid
                )
            );

            // Send out Notification Email with the new Password
            $subject = $siteName.' Account Password Reset';

            $message = '<html><body>';
            $message .= '<h3>'.$subject.'</h3>';
            $message .= '<p>';
            $message .= 'Your Account Password has been Reset<br />';
            $message .= 'Temporary Password: '.$randHash;
            $message .= '</p>';
            $message .= '<p>Once you have signed in, please take the time to update your account password to something you can easily remember.</p>';
            $message .= '<hr />';
            $message .= '<p>Thank You,<br>'.$siteName.'</p>';
            $message .= '</body></html>';

            $headers = 'From: '.$siteName.' <'.$siteEmail.">\r\n";
            $headers .= 'Reply-To: '.$siteEmail."\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            mail($useremail, $subject, $message, $headers);

            echo '1';    // Update completed
        }
    }

    /*
     * Function to check for a duplicate username
     *
     * @param string		$db			The Flat File Connction
     * @param string		$username	The desired Account username passed via the sign in form
     *
     * @return boolean
     */
    function usercheck($db, $username)
    {
        $userdata = '';

        define('USER_ID', 0);
        define('USERNAME', 1);
        define('PASSWORD', 2);
        define('USER_EMAIL', 3);
        define('DATE_CREATED', 4);

        $usrname = htmlspecialchars(alphaNum($username));

        $userdata = $db->selectWhere(
            'users.txt',
            new SimpleWhereClause(USERNAME, '=', $usrname)
        );

        if (empty($userdata)) {
            echo '0';    // No Duplicate found
        } else {
            echo '1';    // Duplicate found
        }
    }

    /*
     * Function to check for a duplicate email
     *
     * @param string		$db			The Flat File Connction
     * @param string		$useremail	The User's Email Address passed via the sign in form
     *
     * @return boolean
     */
    function emailcheck($db, $useremail)
    {
        $userdata = '';

        define('USER_ID', 0);
        define('USERNAME', 1);
        define('PASSWORD', 2);
        define('USER_EMAIL', 3);
        define('DATE_CREATED', 4);

        $useremail = htmlspecialchars($useremail);

        $userdata = $db->selectWhere(
            'users.txt',
            new SimpleWhereClause(USER_EMAIL, '=', $useremail)
        );

        if (empty($userdata)) {
            echo '0';    // No Duplicate found
        } else {
            echo '1';    // Duplicate found
        }
    }

// That's it! Done!
