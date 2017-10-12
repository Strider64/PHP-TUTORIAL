<?php
require_once 'lib/includes/config.php'; // Configuration file for turning error reporting and connection strings to database:
require_once 'lib/functions/php_pdo_functions.inc.php'; // PDO functions and connection:

/*
 * The first thing to do is to make sure you have a database named myCMS and a database table named myBlog.
 * You can run the install file that will create the database and database table by running install.php if you want 
 * or you can create the database and database table yourself. 
 */


/*
 * Read from a database table is pretty straight forward and the only real tough part of it is writing the 
 * query correctly. Visting https://www.mysql.com/ will help you understand MySQl.
 * PDO can better be understand by visiting https://phpdelusions.net/pdo and I highly recommend the website for it
 * has helped me to understand pdo better. One word of advice and that is to ALWAYS use PREPARED statements for
 * security reasons. I also recommend staying up on on PHP, PDO and MYSQL, for all tutorials will eventually become
 * outdated (even this one). I have relocated all the pdo functions and connection over to php_pdo_functions.inc.php file.
 */

/*
 * Check to see if user has clicked on the submit button.
 */
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($submit) && $submit === "submit") {
    /*
     * Grab User's Responses from Form.
     */
    $data['title'] = htmlspecialchars($_POST['title']);
    $data['comment'] = htmlspecialchars($_POST['comment']);

    $result = createBlog($data, $pdo);

    if ($result) {
        header("Location: index.php");
        exit();
    }
}

$rows = readBlog($pdo);
if (isset($_SESSION['user']) && $_SESSION['user']['security'] === 'public') {
    $cmsON = TRUE;
} else {
    $cmsON = FALSE;
}
//echo "<pre>" . print_r($rows, 1) . "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <title>PHP, PDO &amp; MySQL Tutorial</title>
        <!--
        I decided to make an external stylesheet to keep the code down. The stylesheet stays in the same folder
        as the other files. Feel free to use this file or create your own CSS.
        -->
        <link rel="stylesheet" href="lib/css/reset.css">
        <link rel="stylesheet" href="lib/css/style.css">
    </head>
    <body>
        <?php require_once 'lib/includes/heading.inc.php'; ?>
        <div class="container bg-color">
            <?php if ($cmsON) { ?>
                <form id="commentForm" action="" method="post">
                    <fieldset>
                        <legend>Comment Form</legend>
                        <label for="title">Title</label>
                        <input id="title" type="text" name="title" value="" autofocus  tabindex="1">
                        <label class="textBox" for="comment">Comment</label>
                        <textarea id="comment" name="comment" tabindex="2"></textarea>
                        <input type="submit" name="submit" value="submit" tabindex="3">
                    </fieldset>
                </form>
            <?php } else { ?>
            <div id="cmsInfo">
                <h1>PDO &amp; MySQL Tutorial Information</h1>
                <p>You must be registered and login to access the full Content Management System Demo. I'm writing this PHP Tutorial in procedural style with the exception of PDO which forces a person to write it in Object-Oriented Programming Style. This tutorial will show you how to add, update, read and delete content to a MySQL database using PHP PDO.</p>
            </div>
            <?php } ?>
            <div id="articles">
                <?php
                foreach ($rows as $row) {
                    echo '<div class="article">' . "\n";
                    echo "<h2>" . $row['title'] . "</h2>\n";
                    echo '<span class="date">' . $row['display_date'] . '</span>' . "\n";
                    echo '<a class="anchor-tag" href="edit.php?id=' . $row['id'] . '">Edit</a>' . "\n";
                    echo "<p>" . $row['comment'] . "</p>\n";
                    echo "</div>\n";
                }
                ?>                               
            </div>
        </div>
    </body>
</html>