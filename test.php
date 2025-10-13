<?php
// Simple test to check what's causing the error
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Index.php exists: " . (file_exists('index.php') ? 'Yes' : 'No') . "<br>";
echo "Application folder exists: " . (is_dir('application') ? 'Yes' : 'No') . "<br>";

// Test database connection
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'sheikh_library';

try {
    $mysqli = new mysqli($hostname, $username, $password, $database);
    
    if ($mysqli->connect_error) {
        echo "Database Connection Failed: " . $mysqli->connect_error . "<br>";
        echo "Error Number: " . $mysqli->connect_errno . "<br>";
        
        // Try to connect without database to see if MySQL is running
        $mysqli_test = new mysqli($hostname, $username, $password);
        if ($mysqli_test->connect_error) {
            echo "MySQL Server Connection Failed: " . $mysqli_test->connect_error . "<br>";
        } else {
            echo "MySQL Server is running, but database 'sheikh_library' doesn't exist<br>";
            echo "Available databases:<br>";
            $result = $mysqli_test->query("SHOW DATABASES");
            while ($row = $result->fetch_array()) {
                echo "- " . $row[0] . "<br>";
            }
        }
    } else {
        echo "Database Connection: SUCCESS<br>";
        echo "Database Name: " . $database . "<br>";
    }
} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "Testing CodeIgniter load...<br>";

// Test if we can load the basic CodeIgniter
try {
    define('BASEPATH', dirname(__FILE__).'/system/');
    echo "BASEPATH defined<br>";
} catch (Exception $e) {
    echo "CodeIgniter Error: " . $e->getMessage() . "<br>";
}
?>