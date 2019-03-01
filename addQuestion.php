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
            Group 8 Create Questions
        </title>
    </head>

    <body>

        <button onclick=<?php
       echo "\"redirect('profHome.php', '".$credentials["username"]."', '".$credentials["token"]."')\" ";
        ?>type="button">Back</button>
        <br>
        <div id="title" class="center">
            <h1 id="PageTitle">
                Add new Question
            </h1>
        </div>

        <div>
            Question name: <input type="text" id="questionName" form="newQ" value="Name"> 
            <br>
            <textarea rows="20" cols="50" id="description" form="newQ">
                Enter Question here...
            </textarea>
            <br>
            Filters: <input type="text" id="filters" value="Separate Filters by Comma">
            <br>
            <button onclick="submitQuestion()" type="button">Submit</button>
        </div>

        <p id="responseArea">
                    
        </p>
        
    </body>
    
    <script src="GELibrary.js"></script>
    <script>
        function submitQuestion(){
            var req = new XMLHttpRequest();

            req.onreadystatechange = function(){
                //document.getElementById("responseArea").innerHTML = this.responseText;
                var resp = JSON.parse(this.responseText);

                if(resp["SUCCESS"] == "true"){
                    document.getElementById("responseArea").innerHTML = "Question added successfully!";
                }
                else{
                    document.getElementById("responseArea").innerHTML = "There was an error adding your question!";
                }
            };

            req.open("POST", "link.php", true);
            req.setRequestHeader("Content-Type", "application/json");

            var questionName = document.getElementById("questionName").value;
            var desc = document.getElementById("description").value;
            var filters = document.getElementById("filters").value.split(",");
            var jsonReq = JSON.stringify({reqType:"addQuestion", question:{name:questionName, desc:desc, filters: filters}});
            req.send(jsonReq);
        }

    </script>

</html>