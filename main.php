<?php
    session_start();
    $_SESSION["dagrs"] = array();
?>
<html>
    <head>
        <title>Latest Tech Updates</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="mainstyle.css">
    </head>
    <body>
        <h1>Multimedia Data Search: Latest Tech Updates!</h1>
        <p><strong>Directions:</strong> Select a media to display results from.
        Enter keywords to search separated by spaces. </p>
        
        <form action= "process_query.php" method = "post">
            <input type = "checkbox" name = "media[]" value = "document"/>Document</input>
            <input type = "checkbox" name = "media[]" value = "image"/>Image</input>
            <input type = "checkbox" name = "media[]" value = "video"/>Video</input>
            <input type = "checkbox" name = "media[]" value = "audio"/>Audio</input>
            <br/>
            <br/>
            <strong>Query:</strong>
            <br/>
            <input type = "text" name = "query" style="width:400px;height:100px;"/>
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