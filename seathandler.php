<?php

$db_host = 'localhost:3310';
$db_user = 'root'; // Example user
$db_pass = 'root'; // Example password
$db_name = 'project';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

session_start();
$cinid="".$_SESSION["cin"];
$table_name = 'M' . $_SESSION["movie"];

// $method = $_SERVER["REQUEST_METHOD"];

    if (isset($_POST["seats"])){
        foreach ($_POST["seats"] as $val){
            // Insert functionality
            // insert to $table_name which is M+mov id
            // if seat number is greater than 80 then it is a recliner
            if ($val >= 80){
                //set seat recliner
                $insert_sql2 = "INSERT INTO SEAT (SEAT_TYPE,CIN_ID) VALUES (?,?)";
                $stmt2 = $mysqli->prepare($insert_sql2);
                $stmt2->bind_param("si","Recliner",$cinid);
                $stmt2->execute();
            }
            else{
                //set seat
                $insert_sql2 = "INSERT INTO SEAT (SEAT_TYPE,CIN_ID) VALUES (?,?)";
                $stmt2=$mysqli->prepare($insert_sql2);
                $a = "Regular";
                $stmt2->bind_param("si",$a,$cinid);
                $stmt2->execute();
            }
            $insert_sql2 = "SELECT SEAT_ID FROM `$table_name` WHERE SEAT_ID = LAST_INSERT_ID()";
            $stmt = $mysqli->prepare($insert_sql2);
            $stmt->execute();
            $result=$stmt->get_result()->fetch_assoc();
            // set vars here
            $seat_id_value = $result["SEAT_ID"];
            $t_id_value = $val;
            //insert to M.movid table
            $insert_sql = "INSERT INTO `$table_name` (t_id, SEAT_ID) VALUES (?, ?)";
            $stmt = $mysqli->prepare($insert_sql);
            $stmt->bind_param("ii", $t_id_value, $seat_id_value);
            $stmt->execute();
            $stmt->close();
        }
        $target_url = "transaction.php";
        header("Location: ".$target_url);
        die();
    }
    else{
        echo "Sorry there was no value selected";
        echo "<form action=\" try4.php\"><input type=\"submit\" value=\"go back\"></form>";
    }

?>