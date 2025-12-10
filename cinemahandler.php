<html>
<head>
    <title></title>
    <style>
        .box{
            display: block;
            padding: 10px 20px 10px 20px;
            border-radius: 10px;
            width: 80%;
            text-align: left;
            margin-left: auto;
            margin-right: auto;
            border: 1px black solid;
            margin-top: 10px;
            margin-bottom: 10px;
            clear: both;
            background-color: white;
        }

        .box:hover{
            padding-left: 35px;
            background-color: #F1F1F1;
            transition: padding-left 0.2s, background-color 0.2s;
            cursor: pointer;
        }

        p{
            font-weight: 1000;
            font-size: 2em;
        }

    </style>
</head>
<body>



<?php
// add session validation here
// add form validation here -- what if user tampered with the form data from index.php to get to movie.php?
$method = $_SERVER["REQUEST_METHOD"];
if ($method == "GET"){
    echo "you are not allowed to reach this site using this method";
}
else{
    $user='root';
    $password='root';
    $database='project';
    $servername='localhost:3310';
    $mysqli=new mysqli($servername,$user,$password,$database);
    $stmt=$mysqli->prepare("SELECT CIN_ID FROM project.CINEMA WHERE CIN_NAME=?");
    if (isset($_POST["x"]))
    {
        $stmt->bind_param("s",$_POST["x"]);
    }
    else{
        $stmt->bind_param("s",$_POST["status"]);
    }
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $cin = $result;
    if (isset($_POST["insert"])){
        $naenae = $_POST["insert"];
        // search if 
        $stmt=$mysqli->prepare("SELECT CIN_NAME FROM project.CINEMA WHERE CIN_NAME=?");
        $stmt->bind_param("s",$naenae);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if (!is_null($result["CIN_NAME"])){
            $target_url = "try4.php";
            header("Location: ".$target_url);
            die();
        }
        $stmt=$mysqli->prepare("INSERT INTO project.CINEMA(CIN_NAME) VALUES (?)");
        $stmt->bind_param("s",$naenae);
        $stmt->execute();
        
        
        $target_url = "try4.php";
        header("Location: ".$target_url);
        die();
    }
    else{
        
    $cinid = $cin["CIN_ID"];
    // if status value = "update" get cinema name
    // else find cinema name by id, then find screening with cinema id, then find movie with cinema id, then find ticket with cinema id, then find transaction with cinema id, then find ticket with cinema id
    if (isset($_POST["status"]) AND $_POST["status"] == "update"){
        $name = $_POST["cinema"];
        $stmt=$mysqli->prepare("UPDATE CINEMA SET CIN_NAME = ? WHERE CIN_ID = ?");
        $stmt->bind_param("si",$name,$cinid);
        $stmt->execute();
        $target_url = "try4.php";
        header("Location: ".$target_url);
        die();
    }
    else{
        $cin_id_to_delete = $cinid;
        // Set Autocommit to OFF
        $mysqli->query("SET SQL_SAFE_UPDATES = 0;");
        $mysqli ->autocommit(false);
        // Start a transaction
        $is_transaction_ok = true;


        // 1. Delete from TICKET (Child of TRANSACTION, SEAT, MOVIE, CUSTOMER)
        $stmt=$mysqli->prepare("DELETE FROM TICKET WHERE TRAN_ID IN (SELECT TRAN_ID FROM TRANSACTION WHERE CIN_ID = ?)");
        $stmt->bind_param("i",$cin_id_to_delete);
        if (!$stmt->execute()) {
            $is_transaction_ok = false;
            // Note: Logging/error handling is simple here due to constraints
            echo "Error deleting from TICKET: " . mysqli_error($mysqli) . "\n";
        }


        // 2. Delete from TRANSACTION (Child of CINEMA, CUSTOMER)
        
        $stmt=$mysqli->prepare("DELETE FROM TRANSACTION WHERE CIN_ID = ?");
        $stmt->bind_param("i",$cin_id_to_delete);
        if ($is_transaction_ok && !$stmt->execute()) {
            $is_transaction_ok = false;
            echo "Error deleting from TRANSACTION: " . mysqli_error($mysqli) . "\n";
        }


        // 3. Delete from SCREENING (Child of CINEMA, MOVIE)
        $stmt=$mysqli->prepare("DELETE FROM SCREENING WHERE CIN_ID = ?");
        $stmt->bind_param("i",$cin_id_to_delete);
        if ($is_transaction_ok && !$stmt->execute()) {
            $is_transaction_ok = false;
            echo "Error deleting from SCREENING: " . mysqli_error($mysqli) . "\n";
        }


        // 4. Delete from SEAT (Child of CINEMA)
        $stmt=$mysqli->prepare("DELETE FROM SEAT WHERE CIN_ID = ?");
        $stmt->bind_param("i",$cin_id_to_delete);
        if ($is_transaction_ok && !$stmt->execute()) {
            $is_transaction_ok = false;
            echo "Error deleting from SEAT: " . mysqli_error($mysqli) . "\n";
        }


        // 5. Delete from CINEMA (Parent table)
        $stmt=$mysqli->prepare("DELETE FROM CINEMA WHERE CIN_ID = ?");
        $stmt->bind_param("i",$cin_id_to_delete);
        if ($is_transaction_ok && !$stmt->execute()) {
            $is_transaction_ok = false;
            echo "Error deleting from CINEMA: " . mysqli_error($mysqli) . "\n";
        }

        // --- Transaction Management and Close ---

        if ($is_transaction_ok) {
            // Commit the transaction
            $mysqli->commit();
            $target_url="try4.php";
        
            header("Location: ".$target_url);
            die();
        } else {
            // Rollback the transaction
            mysqli_rollback($mysqli);
            echo "Transaction failed. All changes have been rolled back.\n";
        }
    }
    }
    // check status
}
?>
<?php
    
?>
</body>
</html>
