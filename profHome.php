<html>

    <?php

        $inp = file_get_contents('php://input');
        $inp = explode("&", $inp);
        $credentials = array();
        foreach ($inp as $cred){
            $vals = explode("=", $cred);
            $credentials[$vals[0]] = $vals[1];
        }

        //make a curl token request for authentication

        //make a curl get request 

        $ch = curl_init('getRequestOfActiveExams');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $exams = curl_exec($ch); 
        curl_close($ch);

    ?>

    <head>
        <title>
            Group 8 Professor HomePage
        </title>
        <link rel="stylesheet" type="text/css" href="zeta.css">
    </head>

    <body onload="getExams('active')">
        <div class="row">
            <div class="column">
                <div id="title" class="center">
                    <h1 id="PageTitle">
                        Home Page
                    </h1>
                    <p id="responseArea">
                            
                    </p>
                </div>
                <div>
                    <h4>
                        Exams
                        <?php
                            print_r($credentials);
                        ?>
                    </h4>
                    <div id="wrap">
                        <table id="exams">
                            
                        </table>
                    </div>
                </div>
                <div id="formArea" class="center">
                    <div class="leftText">
                        <button type="button">View Grades</button>
                    </div>
                </div>
                <div class="center">
                    <div class="leftText">
                        <button onclick=<?php
                        echo "\"redirect('createExam.php', '".$credentials["username"]."', '".$credentials["token"]."')\" ";
                        ?> type="button">Create an Exam</button>
                    </div>
                </div>
            </div>
        </div>
        
    </body>

    <script src="GELibrary.js"></script>
    <script>

        function getExams(stat){
            var req = new XMLHttpRequest();

            req.onreadystatechange = function(){
                //console.log(this.responseText);
                document.getElementById("exams").innerHTML="<thead><tr><th>Exam ID</th><th>Exam Name</th><th>Difficulty</th><th>Max Score</th></tr></thead>";
                var exams = JSON.parse(this.responseText);
                var strs = ["id", "name", "difficulty", "maxpoints"];
                for(var exam of exams["examList"]){
                    //console.log(exam);
                    if(exam["name"] === ""){
                        continue; // Just because there is some filler data I want to avoid
                    }
                    
                    var row = document.createElement("tr");

                    for(var key of strs){
                        var Label = document.createElement("label");
                        var Text = document.createTextNode(exam[key]);
                        var el = document.createElement("td");

                        Label.appendChild(Text);
                        el.appendChild(Label);
                        row.appendChild(el);
                    }
                    var but = document.createElement("td")
                    var takeTest = document.createElement("button");
                    var buttonText = document.createTextNode("Take Exam");
                    
                    takeTest.value = "Take Exam";
                    takeTest.id = "button" + exam["name"];
                    takeTest.appendChild(buttonText);
                    //takeTest.onclick = getRedirect(exam["id"]);
                    but.appendChild(takeTest);
                    row.appendChild(but);
                    
                    document.getElementById("exams").appendChild(row);
                    //document.getElementById("exams").appendChild(document.createElement("br"));
                }
                    
                
            };

            req.open("POST", "link.php", true);
            req.setRequestHeader("Content-Type", "application/json");

            var jsonReq = JSON.stringify({reqType:"getExams", status: stat});
            req.send(jsonReq);
        }

        function getExam(){

            var req = new XMLHttpRequest();

            req.onreadystatechange = function(){
                document.getElementById("responseArea").innerHTML = this.responseText;
            };

            req.open("POST", "link.php", true);
            req.setRequestHeader("Content-Type", "application/json");

            var jsonReq = JSON.stringify({reqType:"getExam", eid:"15"});
            req.send(jsonReq);
            //document.getElementById("responseArea").innerHTML = "AH";
        }
    </script>

</html>