<?php

    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    //print_r($data);
    $url = "https://web.njit.edu/~st565/";
    $middleUrls = array("login" => $url . "checkAuth.php",
                        "addQuestion" => $url . "addQuestion.php",
                        "getQuestions" => $url . "getQuestions.php",
                        "submitExam" => $url . "createExam.php",
                        "getExams" => $url . "getExams.php",
                        "getExam" => $url . "getExam.php",
                        "submitAttempt" => $url . "gradeTest.php",
                        "getGrades" => "placeholder",
                        "changeGrades" => "placeholder",
                        "checkAuth" => "placeholder");

    $ch = curl_init($middleUrls[$data["reqType"]]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));


    $response = curl_exec($ch); 
    curl_close($ch);
    echo $response;
    if ($data["reqType"] == "getExam"){
        //print_r($data);
    }
    
?>