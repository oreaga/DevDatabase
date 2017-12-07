<?php
    session_start();
    require "Database.php";
    $db_connection = Database::getConnection();
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

    $body = <<< EOBODY
        <h2>Add Data to MMDA</h2>
        
        <h3><strong>Local Directory</strong></h3>
        <form action = "{$_SERVER['PHP_SELF']}" method = "post">
            <input type = "text" class = "pathBox" name = "dirPath"/>
            <input type = "submit" name = "addLocal" value = "Add!"/>
        </form>
        
        <h3><strong>HTML Document</strong></h3>
        <form action = "{$_SERVER['PHP_SELF']}" method = "post">          
            <input type = "text" class = "pathBox" name = "htmlPath"/>
            <input type = "submit" name = "addHtml" value = "Add!"/>
        </form>
        </body>
EOBODY;
    if(isset($_POST['addLocal'])){
        if(!isset($_POST['dirPath'])){
            $body.="Error: No path given! Try again.";
        }else{
            $path = $_POST['dirPath'];
            $command = escapeshellcmd("python Crawler.py local ".$path);
            shell_exec($command);
            $query = file_get_contents("local_inserts.sql");
            $db_connection->multi_query($query);
                
            $body.= "Local directory {$path} added!";  
        }
    }
    if(isset($_POST['addHtml'])){
        if(!isset($_POST['htmlPath'])){
            $body.="Error: No path given! Try again.";
        }else{
            $path = $_POST['htmlPath'];
            $command = escapeshellcmd("python Crawler.py web ".$path);
            shell_exec($command);
            $query = file_get_contents("html_inserts.sql");
            $res = $db_connection->multi_query($query);
             
            $body.= "HTML data for {$path} added!";  
        }
    }

    

    echo $page.$body.$footer;
?>