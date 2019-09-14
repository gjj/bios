<?php
    require_once '../includes/common.php';

    header("Content-Type: application/json");

    $errors = [
        isMissingOrEmpty('r'),
        isMissingOrEmpty('token'),
    ];

    $errors = array_filter($errors);

    if (!isEmpty($errors)) {
        $result = [
            "status" => "error",
            "messages" => array_values($errors)
        ];
    }
    else {
        $request = $_GET['r'];
        $token = $_GET['token'];

        if (verify_token($token)) {

            $requestJson = json_decode($request);

            $jsonError = json_last_error();

            if ($jsonError) {
                $errors = ["Unable to process request parameter: " . $jsonError];
                $result = [
                    "status" => "error",
                    "messages" => array_values($errors)
                ];
            }
            else {
                // Check my JSON request for my compulsory fields.
                $errors = [
                    isMissingOrEmptyJson('userid', $requestJson)
                ];

                $errors = array_filter($errors);

                if (!isEmpty($errors)) {
                    $result = [
                        "status" => "error",
                        "messages" => array_values($errors)
                    ];
                }
                else {
                    $studentDAO = new StudentDAO();
                    $query = $studentDAO->retrieveById($requestJson->userid);
                    
                    if ($query) {
                        $result = [
                            "status" => "success"
                        ];
                        $result = array_merge($result, $query);
                    }
                    else {
                        $errors = ["Invalid user ID."];
                        $result = [
                            "status" => "error",
                            "messages" => array_values($errors)
                        ];
                    }
                }
            }
        }
        else {
            $errors = ["Unauthorised access."];
            $result = [
                "status" => "error",
                "messages" => array_values($errors)
            ];
        }
    }

    echo json_encode($result);