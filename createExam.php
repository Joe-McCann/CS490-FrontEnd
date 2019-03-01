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

        $ch = curl_init('getRequestOfActiveExams');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $exams = curl_exec($ch); 
        curl_close($ch);

    ?>

    <head>
        <title>
            Create Exam
        </title>
        <link rel="stylesheet" type="text/css" href="zeta.css">
    </head>

    <body onload="getQuestions(true)">
        <div class="row">
        <button onclick=<?php
                    echo "\"redirect('profHome.php', '".$credentials["username"]."', '".$credentials["token"]."')\" ";
                ?>type="button">Home</button>
            
        </div>
        
        <div id= "page" class="row">
            <div id="createExam" class="column">
                
                <div id="title" class="center">
                    <h1 id="PageTitle">
                        Create an Exam
                    </h1>
                </div>
                <div>
                    <span>
                        Filters: <input type="text" id="filters" value="Separate Filters by Comma">
                        <button onclick="getQuestions(false)" type="button">Refresh Questions and Filters</button>
                    </span>
                    <div>
                        <div>
                            <h2>List of questions</h2>
                        </div>
                        <div class="list" id="Select">

                        </div>
                    </div>

                    <div>
                        <div>
                            <h2>Selected Questions</h2>
                        </div>
                        <div class="list" id="Chosen">

                        </div>

                        <form>
                            <label for="difficulty">Exam Difficulty: </label>
                            <input type="radio" name="diff" value="Easy" checked> Easy
                            <input type="radio" name="diff" value="Medium" > Medium
                            <input type="radio" name="diff" value="Hard" > Hard<br>
                            Exam Name: <input type="text" id="ExamName"><br>
                        </form>
                    </div>

                    <span>
                        <button onclick="submitExam()" type="button">Publish Exam</button>
                    </span>
                </div>
            </div>

            <div id="addQuestion" class="column">
                <div id="title" class="center">
                    <h1 id="rightColTitle">
                        Add new Question
                    </h1>
                </div>

                <div>
                    <span>
                        <label>
                            Question name: 
                        </label>
                        <input type="text" id="questionName" form="newQ" value="Name"> 
                    </span>
                    <div>
                        <textarea rows="20" cols="50" id="description" form="newQ">
                            Question Description ...
                        </textarea>
                    </div>
                    <form>
                        <label>
                            Filters: 
                        </label>
                        <input type="text" id="filterList" value="Separate Filters by Comma">
                        <button onclick="submitQuestion()" type="button">Submit Question</button>
                    </form>
                </div>
                <p id="responseArea">

                </p>
            </div>        
        </div>
    </body>

    <script src="GELibrary.js"></script>
    <script>

        function submitExam(){
            var req = new XMLHttpRequest();

            req.onreadystatechange = function(){
                //document.getElementById("responseArea").innerHTML = this.responseText;
                
            };

            req.open("POST", "link.php", true);
            req.setRequestHeader("Content-Type", "application/json");

            var qlist = document.getElementsByClassName("Chosen");
            var requestBuild = {difficulty:"Easy", maxpoints:"0", status:"active", name: document.getElementById("ExamName").value, questions: []};
            var maxPoints = 0;
            var diff = "";

            for(var i = 0; i < qlist.length; ++i){
                if (qlist[i].style.display === "none"){
                    continue;
                }
                var baseID = qlist[i].id;
                var name = document.getElementById(baseID + "Name").textContent;
                var points = document.getElementById(baseID + "Value").value;
                maxPoints += parseInt(points);
                question = {qid : i, qname : name, pointVal : points};
                requestBuild.questions.push(question);
            }

            var diffButtons = document.getElementsByName("diff");
            for (var i = 0; i < diffButtons.length; ++i){
                if (diffButtons[i].checked){
                    diff = diffButtons[i].value;
                    break;
                }
            }


            requestBuild.maxpoints = maxPoints;
            requestBuild.difficulty = diff;

            var jsonReq = JSON.stringify({reqType:"submitExam", exam : requestBuild});
            document.getElementById("responseArea").innerHTML = jsonReq;
            req.send(jsonReq);
        }

        function getQuestions(load){
            var filter = [];
            if (!load){
                filter = document.getElementById("filters").value.split(",");
            }

            var req = new XMLHttpRequest();

            req.onreadystatechange = function(){
                //document.getElementById("responseArea").innerHTML = this.responseText;
                document.getElementById("Select").innerHTML = "";
                document.getElementById("Chosen").innerHTML = "";
                console.log(this.responseText);
                var resp = JSON.parse(this.responseText);
                var question;
                var boxes = ["Select", "Chosen"];
                var box;

                for (question of resp["QUESTIONS"]){
                    for (box of boxes){
                        var wrapper = document.createElement("div");
                        var label= document.createElement("label");
                        var descButton = document.createElement("button");
                        var activate = document.createTextNode(question["name"]);
                        var switchButton = document.createElement("button");
                        var switchText = document.createTextNode((box === "Select") ? "Add" : "Remove");
                        var descWrapper = document.createElement("div");
                        var description = document.createTextNode(question["description"]);
                        var t = document.createTextNode("Show Description");

                        switchButton.id = question["name"] + box + "Switch";
                        switchButton.appendChild(switchText);
                        switchButton.onclick = getFunctionQVisible(question["name"], box);    

                        descButton.value = "Show Description";
                        descButton.id = question["name"] + box + "Button";
                        descButton.appendChild(t);
                        descButton.onclick = getFunctionDescVisible(question["name"] + box + "Desc");

                        descWrapper.id = question["name"] + box + "Desc";
                        descWrapper.style.display = "none";
                        descWrapper.appendChild(description)

                        wrapper.className = "question " + box;
                        wrapper.id = question["name"] + box;
                        if (box === "Chosen"){
                            wrapper.style.display = "none";
                        }

                        label.id = question["name"] + box + "Name";


                        label.appendChild(activate);
                        wrapper.appendChild(descButton);
                        wrapper.appendChild(switchButton);   // add the box to the element
                        wrapper.appendChild(label);
                        wrapper.appendChild(descWrapper);

                        if (box === "Chosen"){
                            var plabel= document.createElement("label");
                            var points = document.createElement("input");
                            var pointText = document.createTextNode("Point Value");

                            points.type = "text";
                            points.value = "10";
                            points.id = question["name"] + box + "Value";

                            plabel.appendChild(pointText);
                            plabel.appendChild(points);
                            wrapper.appendChild(document.createElement("br"));
                            wrapper.appendChild(plabel);

                        }
                        // add the label element to your div
                        document.getElementById(box).appendChild(wrapper);
                    }
                }
            };

            req.open("POST", "link.php", true);
            req.setRequestHeader("Content-Type", "application/json");

            var jsonReq = JSON.stringify({reqType:"getQuestions", filters: filter});
            req.send(jsonReq);
        }

        function submitQuestion(){
            var req = new XMLHttpRequest();

            req.onreadystatechange = function(){
                document.getElementById("responseArea").innerHTML = this.responseText;
                var resp = JSON.parse(this.responseText);

                if(resp["SUCCESS"] == "true"){
                    document.getElementById("responseArea").innerHTML += "Question added successfully!";
                }
                else{
                    document.getElementById("responseArea").innerHTML += "There was an error adding your question!";
                }
            };

            req.open("POST", "link.php", true);
            req.setRequestHeader("Content-Type", "application/json");

            var questionName = document.getElementById("questionName").value;
            var desc = document.getElementById("description").value;
            var filters = document.getElementById("filterList").value.split(",");
            console.log(document.getElementById("filterList").value);
            var jsonReq = JSON.stringify({reqType:"addQuestion", question:{name:questionName, desc:desc, filters: filters}});
            req.send(jsonReq);
        }

        function getFunctionDescVisible(id){
            return function() {var x = document.getElementById(id);
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }}
        }

        function getFunctionQVisible(qName, box){
            return function() {
                var otherBox;
                if (box === "Chosen"){
                    otherBox = "Select";
                }
                else{
                    otherBox = "Chosen";
                }

                var clicked = document.getElementById(qName + box);
                var other = document.getElementById(qName + otherBox);
                clicked.style.display = "none";
                other.style.display = "block";
            }
        }

    </script>

</html>