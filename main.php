<?php
    session_start();
    session_unset();
    require "Database.php";
    $db_connection=Database::getConnection();
    $dagrsResult = $db_connection->query("SELECT title from parenttitle");
    $_SESSION["dagrs"] = array();
    while($row = $dagrsResult->fetch_array(MYSQLI_ASSOC)){
        $_SESSION["dagrs"][] = $row["title"];
    }
    //database attributes
    $documents = array("guid","docFormat","author","dateModified","description");
    $images = array("guid","imageWidth","imageHeight","format","author","dateModified","description");
    $audio = array("guid","runningTime","format","author","dateModified","description");
    $videos = array("guid","runningTime","format","author","dateModified","description");
    $_SESSION["data"] = array("documents"=> $documents, "images"=>$images, "audio" => $audio, "videos" => $videos);
?>
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
        <p><strong>Directions:</strong> Select a media to display results from.
        Enter keywords to search separated by spaces. </p>
        
        <form action= "process_query.php" method = "post">
            <input type = "checkbox" name = "media[]" value = "document"/>Document</input>
            <input type = "checkbox" name = "media[]" value = "image"/>Image</input>
            <input type = "checkbox" name = "media[]" value = "video"/>Video</input>
            <input type = "checkbox" name = "media[]" value = "audio"/>Audio</input>
            <br/>
            <br/>
            <div>
                <strong>Query:</strong>
                <input id = "queryBox" type = "text" name = "query"/>
                <select id = "attrBox" name = "queryAttr">
                    <option value ="author">Author</option>
                    <option value ="description">Description</option>
                    <option value = "timeRange">Time Range</option>
                </select>
            </div>
            <br/>
            <br/>
            <input type ="submit" name = "submit" value = "Submit" style="font-weight: 500; font-weight:bold; width:90px;height:30px"/>
            
        </form>
       
    </body>
    <div id = "footer">
        <p>Page By: Dylan O'Reagan and Sahana Rao<br/>
        CMSC424 Fall 2017</p>
    </div>
</html>