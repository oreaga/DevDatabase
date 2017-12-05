<?php
    require_once "Database.php";
    $db_connection = Database::getConnection();
    session_start();
    if($_POST["submit"]){
        if(isset($_POST['media'])){
            $selected = $_POST['media'];
            $queries = array();
            $_SESSION["tables"] = array();
            foreach($selected as $s){
                if($s == "document"){
                   $queries[] = "SELECT * from documents";
                   $_SESSION["tables"][] = "Document";
                }else if($s == "image"){
                    $queries[] = "SELECT * from images";
                    $_SESSION["tables"][] = "Image";
                }else if($s == "audio"){
                    $queries[] = "SELECT * from audio";
                    $_SESSION["tables"][] = "Audio";
                }else if($s == "video"){
                   $queries[] = "SELECT * from videos";
                   $_SESSION["tables"][] = "Video";
                }
                
            }
            if($_POST['queryAttr']=="timeRange"){
                $times = explode(" ",$_POST["query"]);
                if($times[0] < $times[1]){
                    $where = "dateModified >= {$times[0]} and dateModified <= {$times[1]}";
                }else{
                    $where = "dateModified >= {$times[1]} and dateModified <= {$times[0]}";
                }
                
            }else{
                $keywords = explode(" ",$_POST["query"]);
                $words = array();
                $queryAttr = $_POST['queryAttr'];
                foreach($keywords as $key){
                    $words[]="LOWER({$queryAttr}) LIKE LOWER('%{$key}%')";
                }
                $where = implode(" and ",$words);
            }
            $_SESSION["result"] = array();
        
            for($i = 0; $i < count($queries);$i++){
                $queries[$i].= " WHERE {$where};";
                $_SESSION["result"][] = $db_connection->query($queries[$i]);
            }
        }else{
             $_SESSION["error"]="No media types selected";        
        }
    }
    
   include "display_results.php";

?>