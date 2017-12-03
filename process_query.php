<?php
    require_once "Database.php";
    $db_connection = Database::getConnection();
    session_start();
    //dagrs
    if($_POST["submit"]){
        $selected = $_POST['media'];
        if($selected == 0){
            $_SESSION["error"]="No media types selected";         
        }else{
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
            $keywords = explode(" ",$_POST["query"]);
            $words = array();
            
            foreach($keywords as $key){
                $words[]="description LIKE '%{$key}%'";
            }
            $where = implode(" and ",$words);
            $_SESSION["result"] = array();
            for($i = 0; $i < count($queries);$i++){
                $queries[$i].= " WHERE {$where};";
                $_SESSION["result"][] = $db_connection->query($queries[$i]);
            }
            
        }
    }
    
   include "display_results.php";

?>