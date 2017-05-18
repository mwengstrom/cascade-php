<?php
/**
 * Created by PhpStorm.
 * User: ejc84332
 * Date: 8/2/16
 * Time: 2:39 PM
 */

$user = $_POST['user'];

$staging = strstr(getcwd(), "staging/public");
if($staging){
    $to = 'Bethel University Web Development <web-development@bethel.edu>';
}else{
    $to = 'Bethel University Enrollment Data Team <enrollment-data@bethel.edu>';
}


$hash = md5($user['firstName'] . $user['lastName'] .$user['email'] . $_POST['degree-type']);

$subject = 'Homepage Request for Information';
$message = "Request Info ID: " . $hash . "\nFirst Name: " . $user['firstName'] . "\nLast Name: " . $user['lastName'] . "\nEmail: " . $user['email'] . "\nDegree Type: " . $_POST['degree-type'];

$headers = 'From: web-development@bethel.edu' .  "\r\n";
$headers .= 'Bcc: webmaster@bethel.edu' . "\r\n";
$mail = mail($to , $subject , $message, $headers);

if(!$mail){
    header("HTTP/1.1 500 Internal Server Error");
}else{
    $json = array(
        'mail' => $mail,
        'md5hash' => $hash
    );
    echo json_encode($json);
}
