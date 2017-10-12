<?php

function email_confirmation(array $data) {
    /* Setup swiftmailer using your email server information */
    if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
        $transport = Swift_SmtpTransport::newInstance(EMAIL_HOST, EMAIL_PORT); // 25 for remote server 587 for localhost:
    } else {
        $transport = Swift_SmtpTransport::newInstance(EMAIL_HOST, 25);
    }

    $transport->setUsername(EMAIL_USERNAME);
    $transport->setPassword(EMAIL_PASSWORD);

    /* Setup To, From, Subject and Message */
    $message = Swift_Message::newInstance();


    if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
        /* Local Server (Testing Purposes) */
        $comment = "Thank you for taking your time and registering at PHP, PDO and MySQL Tutorial Website.\n Please click or copy the full URL and paste it in your browser: http://localhost:8888/simple_online_commentary/register.php?confirmation=" . $data['confirmation'];
    } else {
        /* Web Server */
        $comment = "Thank you for taking your time and registering at  PHP, PDO and MySQL Tutorial Website.\n Please click or copy the full URL and paste it in your browser: http://www.rocketryoftech.com/register.php?confirmation=" . $data['confirmation'];
    }


    $name = $data['name'];
    $email_to = $data['email'];
    $subject = "Email Account Verification Notice!";


    /*
     * Email Address message is going to
     */
    $message->setTo([
        $email_to => $name // Verification Email Address:
    ]);

    $message->setSubject($subject); // Subject:
    $message->setBody($comment); // Message:
    $message->setFrom('jrpepp@pepster.com', 'John Pepp'); // From and Name:

    $mailer = Swift_Mailer::newInstance($transport); // Setting up mailer using transport info that was provided:
    $result = $mailer->send($message, $failedRecipients);

    if ($result) {
        return TRUE;
    } else {
        echo "<pre>" . print_r($failedRecipients, 1) . "</pre>";
        return FALSE;
    }
}
