<?php
mysqli_report(MYSQLI_REPORT_OFF);
// ⚠️ IMPORTANT: Replace these with your actual database credentials
$db_host = 'localhost:3310';
$db_user = 'root'; // Example user
$db_pass = 'I20!!3568i5'; // Example password
$db_name = 'project';

// Placeholder names for table and values
$table_name = 'M' . $_POST["movie"];
session_start();
$_SESSION["movie"]=$_POST["movie"];
// the t_id_value will be added from the increment
$t_id_value = 101; // Placeholder for t_id (integer type)
$seat_id_value = 5; // Placeholder for SEAT_ID (integer type)

// --- 1. Database Connection (Functional Object Creation) ---
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
    // Stop execution and show the connection error
    die("❌ Connection failed: " . $mysqli->connect_error);
}
// ----------------------------------------------------
## Check for Table Existence
// ----------------------------------------------------
// We use a safe query that attempts to select a row if the table exists.
$check_query = "SELECT 1 FROM `$table_name` LIMIT 1";

// We use the built-in functional mysqli->query() for this check
$check_result = $mysqli->query($check_query);

$table_exists = false;

// We use the try...catch block to handle the mysqli_sql_exception
try {
    // Attempt to run the query
    $check_result = $mysqli->query($check_query);
    
    // If the query succeeds without exception, the table exists.
    if ($check_result) {
        $table_exists = true;
        // Free the result set for SELECT 1 query
        $check_result->free();
    }
} catch (\mysqli_sql_exception $e) {
}

if ($check_result) {
    // ----------------------------------------------------
    // a.) Table Found: Select, Read, and then Insert
    // ----------------------------------------------------
    
    // Select everything and read each row
    $select_query = "SELECT * FROM `$table_name`";
    $result = $mysqli->query($select_query);
    $arr1 = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            //for loop here?
            //store each t_id in an array
            array_push($arr1,$row['t_id']);
            // Processing/reading each row: you can access $row['t_id'] and $row['SEAT_ID']
            // For example, echo "T_ID: " . $row['t_id'] . "\n";
            // The row is successfully read into the $row variable.
        }
        $result->free();
    }

    // display seat
    echo "screen here";
    echo "<form action=\" seathandler.php \" method=\"POST\">";
    echo "<table>";
    $ctr = 0;
    for ($i = 1; $i <= 10; $i++){
        echo "<tr>";
        for ($y = 1; $y <= 10; $y++){
            $ctr = $ctr+1;
            echo "<td>"."<input type=\"checkbox\" name=\"seats[]\" value=\"$ctr\"";
            if (in_array($ctr,$arr1))
            {
                echo " disabled";
            }
            echo ">"."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<input type=\"submit\" value=\"book selected seats\">";
    echo "</form>";
    // // Insert functionality
    // $insert_sql = "INSERT INTO `$table_name` (t_id, SEAT_ID) VALUES (?, ?)";
    // $stmt = $mysqli->prepare($insert_sql);
    // $stmt->bind_param("ii", $t_id_value, $seat_id_value);
    // $stmt->execute();
    // $stmt->close();
    
} else {
    // ----------------------------------------------------
    // b.) Table Not Found: Create the table and then insert
    // ----------------------------------------------------

    $create_table_query = "
        CREATE TABLE `$table_name` (
            t_id INT NOT NULL,
            SEAT_ID INT,
            PRIMARY KEY(t_id),
            FOREIGN KEY(SEAT_ID) REFERENCES SEAT(SEAT_ID)
        )
    ";

    
    $mysqli->query($create_table_query);

    // display seat
    echo "screen here";
    echo "<form action=\" seathandler.php \" method =\"POST\">";
    echo "<table>";
    $ctr = 0;
    for ($i = 1; $i <= 10; $i++){
        echo "<tr>";
        for ($y = 1; $y <= 10; $y++){
            $ctr = $ctr+1;
            echo "<td>"."<input type=\"checkbox\" name=\"seats[]\" value=\"$ctr\">"."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<input type=\"submit\" value=\"book selected seats\">";
    echo "</form>";
    
    // $new_t_id_value = 201; 
    // $new_seat_id_value = 6; 

    // $insert_new_table_sql = "INSERT INTO `$table_name` (t_id, SEAT_ID) VALUES (?, ?)";
    // $new_stmt = $mysqli->prepare($insert_new_table_sql);
    
    // if ($new_stmt === false) {
    //     die("❌ Prepare failed: " . $mysqli->error);
    // }
    
    // $new_stmt->bind_param("ii", $new_t_id_value, $new_seat_id_value);
    // $new_stmt->execute();
    // $new_stmt->close();
}

$mysqli->close();

?>
