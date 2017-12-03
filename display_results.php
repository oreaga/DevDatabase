<?php
    //database attributes
    $documents = array("guid","docFormat","author","dateModified","description","url");
    $images = array("guid","imageWidth","imageHeight","format","author","dateModified","description","url");
    $audio = array("guid","runningTime","format","author","dateModified","description","url");
    $videos = array("guid","runningTime","format","author","dateModified","description","url");
    $data = array("Document"=> $documents, "Image"=>$images, "Audio" => $audio, "Video" => $videos);
    $page = <<< EOBODY
        <html>
            <head>
                <title>Latest Tech Updates</title>
                <meta charset="utf-8">
                <link rel="stylesheet" type="text/css" href="mainstyle.css">
            </head>
            <body>
                <h1>Multimedia Data Search: Latest Tech Updates!</h1>
EOBODY;
    $footer = <<< EOBODY
        <div id = "footer">
            <p>Page By: Dylan O'Reagan and Sahana Rao<br/>
            CMSC424 Fall 2017</p>
        </div>
    
    </html>
EOBODY;

    $body = "";
    if(isset($_SESSION["error"])){
        $body = <<< EOBODY
        <p> Error: No media selected! Try again! </p>
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
EOBODY;
    }
    if(isset($_SESSION["result"])){
        for($i = 0; $i < count($_SESSION["tables"]);$i++){
            $attr = $data["{$_SESSION["tables"][$i]}"];
            $body.= <<< EOBODY
            <strong>{$_SESSION["tables"][$i]} Results</strong>
EOBODY;
            if($_SESSION["result"][$i]->num_rows == 0){
                $body.= "<p>No results to show.</p>";
            }else{
                $body.= <<< EOBODY
                <table>
                    <tr>
EOBODY;
                foreach($attr as $a){
                    $body.= "<th>{$a}</th>";
                }
                $body.="<th>Add to DAGR</th></tr>";
                while($row = $_SESSION["result"][$i]->fetch_array(MYSQLI_ASSOC)){
                   $body.= "<tr>";
                   foreach($attr as $a){
                    $body.="<td>{$row["{$a}"]}</td>";
                   }
                   
                   $body.=<<< EOBODY
                        <td>
                            <form action = "add_to_dagr.php" method = "post">
                                <select>
EOBODY;
                    foreach($_SESSION["dagrs"] as $d){
                        $body.="<option>{$d}</option>";
                    }
                    $body.= <<< EOBODY
                                    <option>[CreateNew]</option>
                                </select>
                                <input type = "submit" name = "addToDagr" value = "Add" />
                            </form>
                        </td>
                    
                   </tr>
EOBODY;
                
                           
                }
                $body.="</table><br><br>";
            }
        }
    }
    
    echo $page.$body.$footer;
    
?>
    