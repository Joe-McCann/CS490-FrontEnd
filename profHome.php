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
    </head>

    <body>
        <div id="title" class="center">
            <h1 id="PageTitle">
                Home Page
            </h1>
        </div>
        <div>
            <h4>
                Active Exams
                <?php
                    print_r($credentials);
                ?>
            </h4>
        </div>
        <div id="formArea" class="center">
            <div class="leftText">
                <button type="button">View Grades</button>
            </div>
        </div>
        <div class="center">
            <div class="leftText">
                <button onclick=<?php
                echo "\"redirect('addQuestion.php', '".$credentials["username"]."', '".$credentials["token"]."')\" ";
                ?>type="button">Add Question</button>
            <br>
            </div>
        </div>
        <div class="center">
            <div class="leftText">
                <button type="button">Create an Exam</button>
                <p id="responseArea">
                    
                </p>
            </div>
        </div>
    </body>

    <script>

        function redirect(redirectURL, username, token){
            var form = document.createElement("form");
            var user = document.createElement("input");
            var tokn = document.createElement("input");

            form.method = "POST";
            form.action = redirectURL;

            user.value=username;
            user.name="username";
            form.appendChild(user);  

            tokn.value=token;
            tokn.name="token";
            form.appendChild(tokn);

            document.body.appendChild(form);

            form.submit();
        }

    </script>

</html>