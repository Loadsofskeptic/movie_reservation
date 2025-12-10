<html>
<head>
    
    <title>Log In</title>
    <style>
        body{
            margin: 0;
            padding: 0;
        }
        .cinema_box{
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

        .cinema_box:hover{
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
      $method = $_SERVER['REQUEST_METHOD'];
      session_start();
      if (!isset($_SESSION['username'])){
        echo "error unauthorized access";
        echo "<br>please login or signup first";
?>
<form action="index.php">
    <input type="submit" value="Login">
</form>
<form action="signup.php">
    <input type="submit" value="Signup">
</form>
<?php
        session_abort();
        die();
      }
      $user='root';
      $password='root';
      $database='project';
      $servername='localhost:3310';
      $mysqli=new mysqli($servername,$user,$password,$database);
      // DISPLAY ALL CINEMAS like a div and a box to be clicked on
      // after clicking on the cinema go to movie.php to select a movie
      // after selecting a movie
      // go to selection.php to check availability
      // after checking for availability, go to seats.php to select your seat
      // after selecting your seat
      // go to confirm.php to ensure the user that he or she likes their choice
      // then go back to cinemas.php
      // click checkout.php

// <input type="text" name="uname" placeholder="name" autocomplete="off">
//       <input type="password" name="pass" placeholder="password" autocomplete="off">
//       <input type="confirm_password" name="cpass" placeholder="confirm password" autocomplete="off">


// $origin="SELECT * FROM restaurant.orders WHERE CUS_NAME='$CUS_NAME'";
    //         // $origin="TRUNCATE TABLE restaurant.orders";
    //         $result=$mysqli->query($origin);
    //         $row=$result->fetch_assoc();

// // DISPLAY
//             // $origin="SELECT * FROM restaurant.orders WHERE CUS_NAME='$CUS_NAME'";
//             $origin1="SELECT * FROM restaurant.orders";
//             $result1=$mysqli->query($origin1);
//             $row1=$result1->fetch_all();
//             // $row=$result->fetch_assoc();
//             foreach ($row1 as $val){
//                 echo "<tr>";
//                 foreach ($val as $val2){
//                     echo "<td>" . $val2."</td>";
//                 }
//                 echo "</tr>";
//             };



    // $origin="SELECT * FROM restaurant.orders WHERE CUS_NAME='$CUS_NAME'";
    //         // $origin="TRUNCATE TABLE restaurant.orders";
    //         $result=$mysqli->query($origin);
    //         $row=$result->fetch_assoc();


    // // Database connection assumed as $conn

    // $stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    // // "ss" indicates the types of parameters: 's' for string
    // $stmt->bind_param("ss", $username, $email);

    // // Set parameters and execute
    // $username = "john_doe";
    // $email = "john@example.com";
    // $stmt->execute();

    // $stmt->close();
    // $conn->close();
?>
    <header>
        <div class="header-div">
            <a href="index.php">logout</a>
             <!--then implement if else statement here  -->
        </div>
    </header>
    <main>

    <?php
    // find name from user account
    // check if is admin
    // add capability to update and edit
    $name = $_SESSION["username"];
    $stmt=$mysqli->prepare("SELECT IS_ADMIN FROM project.USER_ACCOUNT WHERE USER_NAME=?");
    $stmt->bind_param("s",$name);
    $stmt->execute();
    $result=$stmt->get_result()->fetch_assoc();
    
    if($result["IS_ADMIN"]){
        $stmt=$mysqli->prepare("SELECT CIN_NAME FROM project.CINEMA");
        $stmt->execute();
        
        $arr1 = $stmt->get_result()->fetch_all();
        ?>
        <div class="cinema_box">
            <form action="cinemahandler.php" METHOD="POST">
                <input type="text" name="insert" placeholder="">
                <button name="status" type="submit">insert</button>
            </form>
        </div>
        <?php
        foreach ($arr1 as $val){
        ?>
        <!-- IT work but.the.issue.is.that.i.dont.know.how.to.pass.form.data -->
        <!-- bruteforce.form.elements? -->
        <div class="cinema_box">
            <form action="cinemahandler.php" METHOD="POST">
                <input type="text" name="cinema" placeholder="<?=$val[0]?>">
                <button name="status" type="submit" value="update">update</button>
                <input type="hidden" name="x" value="<?=$val[0]?>">
            </form>
            <form action="movie.php" METHOD="POST"> 
                <button name="cinema" placeholder = "" value="<?=$val[0]?>">
                    <p><?= $val[0]?></p>
                </button>
            </form>
            <form action="cinemahandler.php" METHOD="POST">
                <button name="status"type="submit" value="<?= $val[0]?>">delete</button>
            </form>
        </div>
        <?php 
        
        }
        ?>
        <?php
    }
    else{
        $stmt=$mysqli->prepare("SELECT CIN_NAME FROM project.CINEMA");
        $stmt->execute();
        
        $arr1 = $stmt->get_result()->fetch_all();
        
        foreach ($arr1 as $val){
        ?>
        <!-- IT work but.the.issue.is.that.i.dont.know.how.to.pass.form.data -->
        <!-- bruteforce.form.elements? -->
        <form action="movie.php" METHOD="POST"> 
            <button class="cinema_box" name="cinema" placeholder = "" value="<?=$val[0]?>">
            <p><?= $val[0]?></p>
            </button>
        </form>
        <?php 
        }
    }
        ?>
    
    </main>
    <footer>
    </footer>


</body>
</html>