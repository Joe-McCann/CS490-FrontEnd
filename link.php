<?php

    $data = file_get_contents('php://input');
    $data = json_decode($data, true);

    $middleUrls = array("login" => "https://web.njit.edu/~st565/checkAuth.php",
                        "addQuestion" => "https://web.njit.edu/~st565/addQuestion.php",
                        "getQuestions" => "https://web.njit.edu/~st565/getQuestions.php",
                        "submitExam" => "placeholder",
                        "getExams" => "placeholder",
                        "getExam" => "placeholder",
                        "submitAttempt" => "placeholder",
                        "getGrades" => "placeholder",
                        "changeGrades" => "placeholder",
                        "checkAuth" => "placeholder");

    $ch = curl_init($middleUrls[$data["reqType"]]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch); 
    curl_close($ch);
    echo $response;
    if ($data["reqType"] == "addQuestion"){
        print_r($data);
    }
    
?>