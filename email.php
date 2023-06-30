<?php

//Load Composer's autoloader
require("./PHPMailer/PHPMailer.php");
require("./PHPMailer/SMTP.php");
require("./PHPMailer/Exception.php");

// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["yourname"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);
    $country = trim($_POST["country"]);
    $mobile = trim($_POST["mobile"]);
    $contact = trim($_POST["contact_preference"]);

    // Check that data was sent to the mailer.
    if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        exit;
    }
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);    // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'md-in-81.webhostbox.net';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'hemanth@societyforhelp.com';                 // SMTP username
        $mail->Password = 'Hemanth@143';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('hemanth@societyforhelp.com', 'Society For Help form Submission');
        // $mail->addAddress('hemanth5C2@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress('jaganhelp@gmail.com', 'Jagan Help');               // Name is optional
        $mail->addReplyTo('jaganhelp@gmail.com', 'For more information');
        $mail->addCC('societyforhelp@gmail.com');
        // // $mail->addBCC('bcc@example.com');
        
        $mail->isHTML(true);
        $mail->Subject = 'Society for Help: '. $name .' contacted you from '. $country;
        $mail->Body = 'Interested person\'s name : '.$name.' email :'.$email.' mobile :'.$mobile.' <p>His preferred contact is through '. $contact. '</p><p>' . $message . '</p>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        http_response_code(200);
        echo "Thank You! Your message has been sent.";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
exit();
?>