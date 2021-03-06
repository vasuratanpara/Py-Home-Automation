<?php
include('include/DB_Functions.php');
////////////////////////////////////////////////////////////////////////////////////////////
//  Handle Data Recive From Android
///////////////////////////////////////////////////////////////////////////////////////////

// json response array
$response = array("error" => FALSE);

if (isset($_POST['email']) && isset($_POST['msg']) && isset($_POST['host']))
{

    // receiving the post params
    $email = $_POST['email'];
    $msg = $_POST['msg'];
    $host = $_POST['host'];


        ////////////////////////////////////////////////////////////////////////////////////////////////
        //  Fatch Details Of User
        ///////////////////////////////////////////////////////////////////////////////////////////////
        $array = fatch_host($email);
        $local_host = $array[0];
        $global_host = $array[1];

        if($host == 'local')
        {
            $host_ip = $local_host;
            $commands=fatch_user_commands($msg,$email);
            $device_state = (string)$commands[0];
            $number_of_device =(string) $commands[1];
            $newmsg=$host.'&'.$device_state.'&'.$number_of_device;
        }
        else if($host == 'global')
        {
            //////////////////////////////////////////////////////////////////////////////////////////////
            //  Send Data To Registered User
            /////////////////////////////////////////////////////////////////////////////////////////////
            $host_ip = $global_host;
            $commands=fatch_user_commands($msg,$email);
            $device_state = (string)$commands[0];
            $number_of_device =(string) $commands[1];
            $newmsg=$host.'&'.$device_state.'&'.$number_of_device;

        }

        $output=$newmsg;

    // check if server get msg or not
    
        if (android_pi($host_ip,$output))
        {
            // user stored successfully
            $response["error"] = FALSE;
            $response["error_msg"] = "Message send successfully!";
            echo json_encode($response);
        }
        else
        {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in sending message!";
            echo json_encode($response);
        }
}
else
{
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>