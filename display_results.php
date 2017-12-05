<?php
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
    if(isset($_SESSION["error"])){
        unset($_SESSION["error"]);
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
EOBODY;
    }else if(isset($_SESSION["result"])){
        $data = array("Document"=> $_SESSION["data"]["documents"], "Image"=>$_SESSION["data"]["images"],
                      "Audio" => $_SESSION["data"]["audio"], "Video" => $_SESSION["data"]["videos"]);
        for($i = 0; $i < count($_SESSION["tables"]);$i++){
            $attr = $data["{$_SESSION['tables'][$i]}"];
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
                $body.="<th>Add to Category</th></tr>";
                while($row = $_SESSION["result"][$i]->fetch_array(MYSQLI_ASSOC)){
                   $body.= "<tr>";
                   foreach($attr as $a){
                    $body.="<td>{$row["{$a}"]}</td>";
                   }
                    
                   $body.=<<< EOBODY
                        <td>
                            <form id = "addDagr" action = "add_to_dagr.php" method = "post">
                                <select name = "dagrName" id = "dagrName">
EOBODY;
                    foreach($_SESSION["dagrs"] as $d){
                        $body.="<option value = '{$d}'>{$d}</option>";
                    }
                    $tblNms = array("Document"=>"documents","Image"=>"images","Video"=>"videos","Audio"=>"audio");
                    $body.= <<< EOBODY
                                    <option value = "CreateNew">[CreateNew]</option>
                                </select>
                                <button onclick = "createNewDagr()">Add</button>
                                <input type = "hidden" name = "addDagrName" id = "addDagrName"/>
                                <input type = "hidden" name = "childGuid" id = "childGuid" value = "{$row['guid']}"/>
                                <input type = "hidden" name = "childType" id = "childType" value = "{$tblNms[$_SESSION['tables'][$i]]}"/>
                            </form>
                        </td>
                    
                   </tr>
EOBODY;
                
                           
                }
                $body.= <<<EOBODY
                </table>
                
                    <script>
                        function createNewDagr(){
                            if(document.getElementById("dagrName").value == "CreateNew"){
                                var newName = window.prompt("DAGR Name: ", "NewDAGR");
                                document.getElementById("addDagrName").value = newName;
                            }else{
                                document.getElementById("addDagrName").value = document.getElementById("dagrName").value;
                            }
                            document.getElementById("addDagr").submit();
                        }
                    </script>
                
                <br><br>
EOBODY;
            }
        }
    }
    
    echo $page.$body.$footer;
    
?>
    