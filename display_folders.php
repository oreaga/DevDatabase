<?php
    session_start();
    require "Database.php";
    $db_connection=Database::getConnection();
    if(!isset($_SESSION["dagrs"])){
        setupDagrs($db_connection);
    }
    if(!isset($_SESSION["data"])){
     //database attributes
        $documents = array("guid","docFormat","author","dateModified","description");
        $images = array("guid","imageWidth","imageHeight","format","author","dateModified","description");
        $audio = array("guid","runningTime","format","author","dateModified","description");
        $videos = array("guid","runningTime","format","author","dateModified","description");
        $_SESSION["data"] = array("documents"=> $documents, "images"=>$images, "audio" => $audio, "videos" => $videos);
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
                <li><a href="display_folders.php">Add Data</a></li>
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

    $body = "<div id = 'container'><div id = 'dagrList'><strong>Categories</strong><form action ='{$_SERVER['PHP_SELF']}' method = 'post'><ul>";
    foreach($_SESSION["dagrs"] as $dagrTitle){
        $body.="<li><input type='submit' name = 'categName' value = '{$dagrTitle}'/></li>";
    }
    $body.= "</ul></form></div><div id = 'contentsList'>";
    if(isset($_POST['categName'])|| isset($_POST['delete'])){
        $dagrTitle;
        if(isset($_POST['categName'])){
            $dagrTitle = $_POST['categName'];
        }else{
            $dagrTitle = $_POST['deleteDagr'];
        }
        $cRes = $db_connection->query("SELECT pc.cid FROM parentchild pc, parenttitle pt WHERE pt.title = '{$dagrTitle}' and pc.pid = pt.pid;");
        $children = getChildren($db_connection,$cRes);
        $dagrContents = array("documents"=>array(),"images"=>array(),"audio"=>array(),"videos"=>array());
        foreach($children as $cid){
            $getTypeQ = "SELECT type FROM childtype WHERE cid = '{$cid}';";
            $tResult = $db_connection->query($getTypeQ);
            $type = $tResult->fetch_row()[0];
            $getItemQ = "SELECT * FROM {$type} WHERE guid = '{$cid}';";
            $res = $db_connection->query($getItemQ);
            while($d = $res->fetch_array(MYSQLI_ASSOC)){
                array_push($dagrContents[$type], $d);
            }
        }
        if(isset($_POST['delete'])){
            $body.="<h2> DAGR : {$dagrTitle} Deleted</h2>";
            $pRes = $db_connection->query("SELECT pid FROM parenttitle WHERE title = '{$dagrTitle}'");
            $pid = $pRes->fetch_row()[0];
            deleteDagr($db_connection,$dagrTitle,$pid,$children);
            unset($_SESSION["dagrs"]);
            setupDagrs($db_connection);
        }else{
    
            $body.="<h2> DAGR : {$dagrTitle} </h2>";
        }
        $body.= printDagrContents($dagrContents);
        
        $body.= <<< EOBODY
           <br>
           <form action = "{$_SERVER['PHP_SELF']}" method = "post">
                <input type = "hidden" name = "deleteDagr" value = "{$dagrTitle}"/>
                <input type = "submit" name = "delete" value = "Delete"/>
            </form>
EOBODY;
        
        $body.="</div></div></body>";
            
        
    }else{
        $body.= "</div></div></body>";
    }

echo $page.$body.$footer;

function setupDagrs($db_connection){
    $dagrsResult = $db_connection->query("SELECT title from parenttitle");
    $_SESSION["dagrs"] = array();
    while($row = $dagrsResult->fetch_array(MYSQLI_ASSOC)){
        $_SESSION["dagrs"][] = $row["title"];
    }
}
function getChildren($db,$result){
    $children = array();
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $children[]=$row["cid"];
        $res = $db->query("SELECT cid FROM parentchild WHERE pid = '{$row['cid']}';");
        if($res->num_rows > 0){
            return array_merge($children,getChildren($db,$res));
        }else{
            return $children;
        }
    }
}
function deleteDagr($db,$dagrTitle,$pid,$children){
    //delete title
     $db->query("DELETE FROM parenttitle WHERE title='{$dagrTitle}';");
     foreach($children as $cid){
        //delete parentchild
        $db->query("DELETE FROM parentchild WHERE cid='{$cid}' and pid = '{$pid}';");
        //delete child type
        $db->query("DELETE FROM childtype WHERE cid = '{$cid}';");
     }
    
               
}
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