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
    $stmt->bind_param("s",$_POST["cinema"]);
    $stmt->execute();

    session_start();
    $result = $stmt->get_result()->fetch_assoc();

    $_SESSION['cin'] = $result['CIN_ID'];
    $stmt=$mysqli->prepare("SELECT MOV_ID FROM project.SCREENING WHERE CIN_ID=?");
    $stmt->bind_param("i",$result["CIN_ID"]);
    $stmt->execute();
    $result2=$stmt->get_result()->fetch_all();
    foreach ($result2 as $val){
        $stmt=$mysqli->prepare("SELECT * FROM project.MOVIE WHERE MOV_ID=?");
        $stmt->bind_param("i",$val[0]);
        $stmt->execute();
        $result3=$stmt->get_result()->fetch_assoc();
        ?>
        <!-- echo $result3["MOV_TITLE"]."<br>"; -->
        <form action="seat.php" METHOD="POST"> 
        <button class="box" name="movie" value="<?=$val[0]?>" >
        <p><?=$result3["MOV_TITLE"];?></p>
        <pre>
        Runtime: <?=$result3["MOV_RUNTIME"]?>
        Php <?=$result3["MOV_TICKET_PRICE"]?>
        Showing: <?=$result3["MOV_SCRN_TIME"]?>
        <?=$result3["MOV_RATING"]?>
        <?=$result3["MOV_FORMAT"]?>
        </pre>
        </button>
        </form>        
        <?php
    }
}

//  - - - - -- -- -- - -- -- -- - - -- -- -- -- - 
// UNCOMMENT THIS TO RUN MOCK DATA OF MOVIE
//  - -- - - - -- - -- 

//     $myfile = fopen("movie.txt", "r") or die("unable to open file");
//     echo "cinema movie date time screentime<br>";
//     while (!feof($myfile)){
//         $read_line= fgets($myfile);
//         $arrstr = explode("|",$read_line);
//         $cin;
//         $mov;
//         // if ($arrstr[0] ==$_POST["cinema"]) 
//         $movf=fopen("moviescrtime.txt","r") or die("unable to open file");
//         $scrtime = "";
//         $rating = "";
//         $tckt_price = "";
//         $mov_start_scr_time;
//         $mov_end_scr_time;
//         // two birds with one stone
//         $mov_format="2D_20.00|IMAX 3D_70.00|IMAX 2D_50.00|Director's Club_120.00|IMAX_60.00|4DX/Premiere_80.00|ATMOS_30.00|A-Luxe_45.00|Premiere_30.00|VIP_100.00|Platinum Cinema_90.00|Dolby Atmos_35.00|Premier_30.00|Arthouse Premiere_20.00|Special Screening_20.00|Independent_20.00|Arthouse_20.00";
//         $curr_mov_format ="2D";
//         $holder = "";
//         $datehere = "";
//         while (!feof($movf)){
//             $read_sline=fgets($movf);
//             $arrstr2 = explode("|", $read_sline);
//             // explode the first index to remove the formats (IMAX etc.)
//             $arrstr3 = explode(" (", $arrstr[1]);
//             $referrer = "";
//             if (count($arrstr3)>1){
//                 $referrer = explode(")",$arrstr3[1])[0];
//                 $randomarr = explode("|",$mov_format);
//                 foreach ($randomarr as $val2){
//                     if (trim($referrer) == trim(explode("_",$val2)[0])){
//                         $curr_mov_format=$val2;
//                         break;
//                     }
//                 }
//             }
//             if ($arrstr2[0] == $arrstr3[0]){
//                 $scrtime = $arrstr2[1];
//                 $rating = $arrstr2[2];
//                 $eh = explode("_",$curr_mov_format);
//                 if (count($eh) > 1){
//                     $tckt_price = (float) $arrstr2[4] + $eh[1];
//                 }
//                 else{
//                     $tckt_price = (float) $arrstr2[4];
//                 }
//                 $curr_mov_format = $eh[0];
//                 $mov_start_scr_time= (string) $arrstr2[3];
//                 $mov_end_scr_time= (string) $arrstr2[5];
//                 break;
//             }
//         }
        
//         for ($val = 0;$val<count($arrstr); $val++){
//             if ($val == 0){
//                 $cin = $arrstr[$val];
//             }
//             else if($val == 1){
//                 $mov = $arrstr[$val];
//             }
//             else{
//                 if($val%2==0){
//                     // date
//                     $datehere = $arrstr[$val];
//                 }
//                 else{
//                     //time
//                     $stmt=$mysqli->prepare("INSERT INTO project.MOVIE(MOV_TITLE, MOV_RUNTIME, MOV_TICKET_PRICE, MOV_SCRN_TIME, MOV_FORMAT, MOV_RATING) VALUES (?,?,?,?,?,?)");
//                     $datentime = $datehere." ".$arrstr[$val];
//                     $stmt->bind_param("ssdsss",$mov,$scrtime,$tckt_price,$datentime,$curr_mov_format,$rating);
//                     $stmt->execute();
//                     $stmt2=$mysqli->prepare("INSERT INTO project.SCREENING(CIN_ID, MOV_ID,SCREENING_STR_DATE,SCREENING_END_DATE) VALUES(?,?,?,?)");
//                     $stmt3=$mysqli->prepare("SELECT CIN_ID FROM project.CINEMA WHERE CIN_NAME = ?");
//                     $stmt3->bind_param("s",$cin);
//                     $stmt3->execute();
//                     $cinid = $stmt3->get_result()->fetch_assoc();
//                     $stmt4=$mysqli->prepare("SELECT MOV_ID FROM project.MOVIE WHERE MOV_ID = LAST_INSERT_ID()");
//                     $stmt4->execute();
//                     $movid = $stmt4->get_result()->fetch_assoc();
//                     $stmt2->bind_param("iiss",$cinid["CIN_ID"],$movid["MOV_ID"],$mov_start_scr_time,$mov_end_scr_time);
//                     $stmt2->execute();
                    
//                     echo $mov_start_scr_time." ".$mov_end_scr_time." ".$cin." ".$mov." ".$datehere." ".$arrstr[$val]." ".$scrtime." ".$rating." ".$tckt_price." ".$curr_mov_format."<br/>";
//                 }
                
// //                 CIN_ID INT NOT NUL0L,
// // MOV_ID INT NOT NULL,
// // -- screening ref is a composite key 
// // SCREENING_REF INT NOT NULL,
// // SCREENING_STR_DATE DATE NOT NULL,
// // SCREENING_END_DATE DATE NOT NULL,
//             }
//             // echo $arrstr."<br>";
//         }
        
//         fclose($movf);
//     }
//     fclose($myfile);

//  - - - - -- -- -- - -- -- -- - - -- -- -- -- - 
// UNCOMMENT THIS TO RUN MOCK DATA OF MOVIE
//  - -- - - - -- - -- 


?>
</body>
</html>
