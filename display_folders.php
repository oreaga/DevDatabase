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
    
    $body= "<section class ='container'>
    <div id = 'dagrList'><strong>Categories</strong><form action ='{$_SERVER['PHP_SELF']}' method = 'post'><ul>";
    foreach($_SESSION["dagrs"] as $dagrTitle){
        $body.="<li><input type='submit' name = 'categName' value = '{$dagrTitle}'/></li>";
    }
    $body.= "</ul></form></div>";
    if(isset($_POST['categName']) || isset($_POST['delete']) || isset($_POST['updateDesc'])
       || isset($_POST['removeFromCateg']) || isset($_POST['deleteItem'])){
        $body.="<div id ='contentsList'>";
        $dagrTitle;
        if(isset($_POST['categName'])){
            $dagrTitle = $_POST['categName'];
        }else if(isset($_POST['delete'])){
            $dagrTitle = $_POST['deleteDagr'];
        }else{
            $dagrTitle = $_POST['dagrTitle'];
        }
        if(isset($_POST['newitemDesc'])&& $_POST['newitemDesc'] != ""){
            echo $_POST['newitemDesc'];
            $db_connection->query("UPDATE {$_POST['descItemType']} SET description='{$_POST['newitemDesc']}' WHERE
                                            guid = '{$_POST['descItemGuid']}';");
            $body.="<p>Description updated.</p>";
        }
        if(isset($_POST['removeFromCateg'])){
            removeFromCateg($db_connection,$dagrTitle);
            $body.="<p>{$_POST['remItemGuid']} was removed.</p>";
        }
        if(isset($_POST['deleteItem'])){
           deleteItem($db_connection,$dagrTitle);
           $body.="<p>{$_POST['delItemGuid']} was deleted.</p>";
        }
        
        if(isset($_POST['delete'])){
            $body.="<h2> {$dagrTitle} Deleted</h2>";
            $pRes = $db_connection->query("SELECT pid FROM parenttitle WHERE title = '{$dagrTitle}';");
            $pid = $pRes->fetch_row()[0];
            $cRes = $db_connection->query("SELECT pc.cid FROM parentchild pc, parenttitle pt WHERE pt.title = '{$dagrTitle}' and pc.pid = pt.pid;");
            $children = getChildren($db_connection,$cRes);
            deleteDagr($db_connection,$dagrTitle,$pid,$children);
            unset($_SESSION["dagrs"]);
            setupDagrs($db_connection);
        }else{
    
            $body.="<h2> {$dagrTitle} </h2>";
            $dagrContents = getCategory($db_connection,$dagrTitle);
            $body.= printDagrContents($dagrContents,$dagrTitle);
            $body.= <<< EOBODY
                
                <form action = "{$_SERVER['PHP_SELF']}" method = "post">
                     <input type = "hidden" name = "deleteDagr" value = "{$dagrTitle}"/>
                     <input type = "submit" name = "delete" value = "Delete {$dagrTitle}"/>
                 </form>
EOBODY;
        
        }                

        $body.="</div></section>";        
    }else{
        $body.= "</section>";
        
    }
    $body.=<<<EOBODY
        <br><br>
        <script type="text/javascript" src="jquery-1.8.3.js"></script>
        <script type="text/javascript" src = "functions.js"></script>
        </body>
EOBODY;
    echo $page.$body.$footer;

function setupDagrs($db_connection){
    $dagrsResult = $db_connection->query("SELECT title from parenttitle");
    $_SESSION["dagrs"] = array();
    while($row = $dagrsResult->fetch_array(MYSQLI_ASSOC)){
        $_SESSION["dagrs"][] = $row["title"];
    }
}
function deleteItem($db_connection,$dagrTitle){
    $pRes = $db_connection->query("SELECT pid FROM parenttitle WHERE title = '{$dagrTitle}';");
    $pid = $pRes->fetch_row()[0];
    $db_connection->query("DELETE FROM parentchild WHERE pid = '{$pid}' and cid = '{$_POST['delItemGuid']}';");
    $db_connection->query("DELETE FROM childtype WHERE cid ='{$_POST['delItemGuid']}' and type ='{$_POST['delItemType']}';");
    $db_connection->query("DELETE FROM {$_POST['delItemType']} WHERE guid = '{$_POST['delItemGuid']}';");
}
function removeFromCateg($db_connection,$dagrTitle){
    $pRes = $db_connection->query("SELECT pid FROM parenttitle WHERE title = '{$dagrTitle}';");
    $pid = $pRes->fetch_row()[0];
    $db_connection->query("DELETE FROM parentchild WHERE pid = '{$pid}' and cid = '{$_POST['remItemGuid']}';");
    $db_connection->query("DELETE FROM childtype WHERE cid ='{$_POST['remItemGuid']}' and type ='{$_POST['remItemType']}';");
    //if category is empty delete category
    $res = $db_connection->query("SELECT cid from parentchild WHERE pid = '{$pid}';");
    if(!$res || $res->num_rows ==0){
        $db->query("DELETE FROM parenttitle WHERE title='{$dagrTitle}';");
    }
}
function getChildren($db,$result){
    $children = array();
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $children[]=$row["cid"];
        $res = $db->query("SELECT cid FROM parentchild WHERE pid = '{$row['cid']}';");
        if($res && $res->num_rows > 0){
            return array_merge($children,getChildren($db,$res));
        }
    }
    return $children;
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
function getCategory($db_connection, $dagrTitle){
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
    return $dagrContents;
}
function printDagrContents($contents,$dagrTitle){
        $result = "";
        foreach($_SESSION["data"] as $table=>$attr){
            if(array_key_exists($table,$contents) and count($contents[$table]) != 0){
                $result.="<strong>{$table}</strong>";
                $result.= "<table><tr>";
                foreach($attr as $a){
                        $result.= "<th>{$a}</th>";
                    }
                    $result.= "<th>Organize</th>";
                    $result.= "</tr>";
                foreach($contents[$table] as $item){
                    $result.="<tr>";
                    foreach($attr as $a){
                        $result.="<td>{$item[$a]}</td>";
                    }
                    $result.= <<<EOBODY
                        <td>
                        <form id = "descUpdate" action = "{$_SERVER['PHP_SELF']}" method = "post">
                            <input type = "hidden" name = "descItemGuid" value = "{$item['guid']}"/>
                            <input type = "hidden" name = "descItemType" value = "{$table}"/>
                            <input type = "hidden" name = "newitemDesc" id = "newitemDesc"/>
                            <input type = "hidden" name = "dagrTitle" value = "{$dagrTitle}"/>
                            <button name = "updateDesc" id = 'updateDesc'/>Update Description</button>
                        </form>
                           
                        <br>
                        <form action = "{$_SERVER['PHP_SELF']}" method = "post">
                            <input type = "hidden" name = "remItemGuid" value = "{$item["guid"]}"/>
                            <input type = "hidden" name = "remItemType" value = "{$table}"/>
                            <input type = "hidden" name = "dagrTitle" value = "{$dagrTitle}"/>
                            <input type = "submit" name = "removeFromCateg" value = "Remove From"/>
                        </form>
                        <br>
                        <form action = "{$_SERVER['PHP_SELF']}" method = "post">
                            <input type = "hidden" name = "delItemGuid" value = "{$item["guid"]}"/>
                            <input type = "hidden" name = "delItemType" value = "{$table}"/>
                            <input type = "hidden" name = "dagrTitle" value = "{$dagrTitle}"/>
                            <input type = "submit" name = "deleteItem" value = "Delete"/>
                        </form>
                        </td></tr>
EOBODY;
                }
                $result.="</table>";
            }
        }
        
       
        return $result;
    }
?>