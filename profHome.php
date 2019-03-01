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

    <body>
        <div class="row">
            <div class="column">
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
                        echo "\"redirect('createExam.php', '".$credentials["username"]."', '".$credentials["token"]."')\" ";
                        ?> type="button">Create an Exam</button>
                        <p id="responseArea">
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script src="GELibrary.js"></script>
    <script>

    </script>

</html>