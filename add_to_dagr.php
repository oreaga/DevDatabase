<?php
     require_once "Database.php";
    $db_connection = Database::getConnection();
    session_start();
    function GUID(){
        if (function_exists('com_create_guid') === true){
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
                       mt_rand(0, 65535), mt_rand(0, 65535),
                       mt_rand(0, 65535), mt_rand(16384, 20479),
                       mt_rand(32768, 49151), mt_rand(0, 65535),
                       mt_rand(0, 65535), mt_rand(0, 65535));
    }
    
      $page = <<< EOBODY
        <html>
    <head>
        <title>Multimedia Data Aggregator</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="mainstyle.css">
    </head>
    
    
        <h1>Multimedia Data Aggregator</h1>
        <div id = "navBar">
            <ul>
                <li><a href='main.php'>Homepage</a></li>
                <li><a href="display_folders.php">Categories</a></li>
                <li><a href="display_duplicates.php">Find Duplicates</a></li>
                <li><a href="add_data.php">Add Data</a></li>
            </ul>
        </div>
        <body>
        <br>
EOBODY;
    $footer = <<< EOBODY
        <div id = "footer">
            <p>Page By: Dylan O'Reagan and Sahana Rao<br/>
            CMSC424 Fall 2017</p>
        </div>
    
    </html>
EOBODY;

    $body = "";
    if(isset($_POST['addDagrName'])){
        $dagrName = $_POST['addDagrName'];
        if(!in_array($dagrName,$_SESSION['dagrs'])){
            $_SESSION["dagrs"][] = $dagrName;
            $guid = GUID();
            //insert new title
            $insertQ = "INSERT INTO parenttitle (pid,title) VALUES ('{$guid}','{$dagrName}');";
            $db_connection->query($insertQ);
        }
        //insert new item to dagr
        $pidQ = "SELECT pid FROM parenttitle WHERE title = '{$dagrName}';";
        $pid;
        $pResult = $db_connection->query($pidQ);
        while($row = $pResult->fetch_array(MYSQLI_ASSOC)){
            $pid = $row["pid"];
        }
        
        if(isset($pid) and isset($_POST["childGuid"])){
            $stmt = $db_connection->prepare("INSERT INTO parentchild (pid,cid) VALUES(?, ?);");
            if ($stmt) {
                $stmt->bind_param("ss", $pid,$_POST['childGuid']);
                $stmt->execute();
                $stmt->close();
            }
            $stmt = $db_connection->prepare("INSERT INTO childtype (cid,type) VALUES(?, ?);");
            if ($stmt) {
                $stmt->bind_param("ss", $_POST['childGuid'],$_POST['childType']);
                $stmt->execute();
                $stmt->close();
            }
            $dagrContents = array("documents"=>array(),"images"=>array(),"audio"=>array(),"videos"=>array());
            $getChildQ = "SELECT cid FROM parentchild WHERE pid = '{$pid}';";
            $cResult = $db_connection->query($getChildQ);
            while($row = $cResult->fetch_array(MYSQLI_ASSOC)){
                $cid = $row["cid"];
                $getTypeQ = "SELECT type FROM childtype WHERE cid = '{$cid}';";
                $type;
                $tResult = $db_connection->query($getTypeQ);
                while($r = $tResult->fetch_array(MYSQLI_ASSOC)){
                    $type = $r["type"];
                }
                $getItemQ = "SELECT * FROM {$type} WHERE guid = '{$cid}';";
                $res = $db_connection->query($getItemQ);
                while($d = $res->fetch_array(MYSQLI_ASSOC)){
                    array_push($dagrContents[$type], $d);
                }
            }
            $body.= "<h2> DAGR : {$dagrName} </h2>";
            $body.= printDagrContents($dagrContents);
        }

    }else{
        $body = "<p>Error: No DAGR selected!</p>";
    }
    
    echo $page.$body.$footer;
    
    function printDagrContents($contents){
        $result = "";
        foreach($_SESSION["data"] as $table=>$attr){
            if(array_key_exists($table,$contents) and count($contents[$table]) != 0){
                $result.="<strong>{$table}</strong>";
                $result.= "<table><tr>";
                foreach($contents[$table] as $item){
                    foreach($attr as $a){
                        $result.= "<th>{$a}</th>";
                    }
                    $result.= "</tr><tr>";
                    foreach($attr as $a){
                        $result.="<td>{$item[$a]}</td>";
                    }
                    $result.= "</tr></table>";
                }
            }
        }
        return $result;
    }
                 
    
?>