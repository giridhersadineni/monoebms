
<?php
$error = '';
session_start();

if (isset($_GET['ht'])) {
    if (empty($_GET['ht']) || empty($_GET['dob'])) {
        $error = "Hallticket or Date of birth is invalid";
    } else {
// Define $username and $password
        $haltckt = $_GET['ht'];
        $dob = $_GET['dob'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
        $error = ''; // Variable To Store Error Message

        include 'config.php';

        $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

// To protect MySQL injection for Security purpose
        $haltckt = stripslashes($haltckt);
        $dob = stripslashes($dob);

        $haltckt = mysqli_real_escape_string($conn, $haltckt);
        $dob = mysqli_real_escape_string($conn, $dob);

// Selecting Database
        // SQL query to fetch information of registerd users and finds user match.
        $result = $conn->query("select * from students where dob='$dob' AND haltckt='$haltckt'");

        if ($result->num_rows == 1) {
            $id = 0;
            // Starting Session
            while ($row = mysqli_fetch_assoc($result)) {

                setcookie("userfilename", $row["imgurl"], time() + 3600);
                $id = $row['id'];
                setcookie("aadhar", $row['aadhar']);
                setcookie("name", $row["sname"]);
                $scheme = $row['SCHEME'] ?? null;
            }
            $_SESSION['login'] = $haltckt; // Initializing Session
            setcookie("userid", $_POST['haltckt'], time() + 3600);

            $_SESSION['hallticket'] = $haltckt;
            $_SESSION['aadhar'] = $aadhar;
            mysqli_close($conn);

            // Scheme-2026 students: redirect to new portal via signed SSO token
            if ($scheme === '2026') {
                include 'sso_config.php';
                $ts  = time();
                $sig = hash_hmac('sha256', $haltckt . '|' . $ts, $sso_secret);
                $url = $sso_target . '?' . http_build_query([
                    'ht'  => $haltckt,
                    'ts'  => $ts,
                    'sig' => $sig,
                ]);
                header('Location: ' . $url);
                exit;
            }
            header("location:/ebms/students/welcome.php");
        } else {
            $error = "Hallticket or Date of birth is invalid";
            $_SESSION = array();
            session_destroy();
        }
        mysqli_close($conn); // Closing Connection
    }
}
?>