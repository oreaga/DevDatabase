<?php
    session_start();
    require "Database.php";
    $db_connection=Database::getConnection();
    if(!isset($_SESSION["dagrs"])){
        setupDagrs($db_connection);
    }
     //database attributes
        $documents = array("docFormat","author","dateModified","description");
        $images = array("imageWidth","imageHeight","format","author","dateModified","description");
        $audio = array("runningTime","format","author","dateModified","description");
        $videos = array("runningTime","format","author","dateModified","description");
        $_SESSION["dupData"] = array("documents"=> $documents, "images"=>$images, "audio" => $audio, "videos" => $videos);
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
    foreach($_SESSION["dupData"] as $table=>$attr){
        $dupQ = "SELECT t1.* FROM {$table} t1, {$table} t2 WHERE t1.guid!=t2.guid and ";
        $whereParts = array();
        foreach($attr as $a){
            $whereParts[]= "t1.{$a}=t2.{$a}";
        }
        $where = implode(" and ",$whereParts);
        $dupQ.="{$where};";
        $dupResult = $db_connection->query($dupQ);
        $dagrContents = array("documents"=>array(),"images"=>array(),"audio"=>array(),"videos"=>array());
        if($dupResult){
            while($d = $dupResult->fetch_array(MYSQLI_ASSOC)){
                array_push($dagrContents[$table], $d);
            }
        }
    }
    $body.="<h2> Duplicates Found </h2>";
    $body.= printDagrContents($dagrContents);
    
    $body.="</body>";
        
    echo $page.$body.$footer;

function setupDagrs($db_connection){
    $dagrsResult = $db_connection->query("SELECT title from parenttitle");
    $_SESSION["dagrs"] = array();
    while($row = $dagrsResult->fetch_array(MYSQLI_ASSOC)){
        $_SESSION["dagrs"][] = $row["title"];
    }
}

function printDagrContents($contents){
    $result = "";
    foreach($_SESSION["data"] as $table=>$attr){
        $result.="<strong>{$table}</strong>";
        if(array_key_exists($table,$contents) and count($contents[$table]) != 0){
            $result.= "<table><tr>";
            foreach($attr as $a){
                    $result.= "<th>{$a}</th>";
                }
            $result.= "</tr>";
            foreach($contents[$table] as $item){
                $result.="<tr>";
                foreach($attr as $a){
                    $result.="<td>{$item[$a]}</td>";
                }
                $result.= "</tr>";
            }
            $result.="</table>";
        }else{
            $result.="<p>No results to show.</p>";
        }
    }
    return $result;
}
?>