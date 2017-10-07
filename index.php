<?php
require_once 'config.php'; // Configuration file for turning error reporting and connection strings to database:

/*
 * The first thing to do is to make sure you have a database named myCMS and a database table named myBlog.
 * You can run the install file that will create the database and database table by running install.php if you want 
 * or you can create the database and database table yourself. 
 */

/*
 * Establish a database connection.
 */
$db_options = [
    /* important! use actual prepared statements (default: emulate prepared statements) */
    PDO::ATTR_EMULATE_PREPARES => false
    /* throw exceptions on errors (default: stay silent) */
    , PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    /* fetch associative arrays (default: mixed arrays)    */
    , PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
$pdo = new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME . ';charset=utf8', DATABASE_USERNAME, DATABASE_PASSWORD, $db_options);

/*
 * Check to see if user has clicked on the submit button.
 */
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($submit) && $submit === "submit") {
    /*
     * Grab User's Responses from Form.
     */
    $title = htmlspecialchars($_POST['title']);
    $comment = htmlspecialchars($_POST['comment']);
    /*
     * Insert Into Database Table myBlog.
     */
    $query = 'INSERT INTO myBlog(title, comment, date_added) VALUES (:title, :comment, NOW())';
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([':title' => $title, ':comment' => $comment]);

    if ($result) {
        header("Location: index.php");
        exit();
    }
}

/*
 * Read from a database table is pretty straight forward and the only real tough part of it is writing the 
 * query correctly. Visting https://www.mysql.com/ will help you understand MySQl.
 * PDO can better be understand by visiting https://phpdelusions.net/pdo and I highly recommend the website for it
 * has helped me to understand pdo better. One word of advice and that is to ALWAYS use PREPARED statements for
 * security reasons. I also recommend staying up on on PHP, PDO and MYSQL, for all tutorials will eventually become
 * outdated (even this one).
 */

function readBlog($pdo = NULL) {
    $query = 'SELECT id, title, comment,  DATE_FORMAT(date_added, "%W, %M %e, %Y") as display_date, date_added as my_date FROM myBlog ORDER BY my_date DESC';
    $stmt = $pdo->query($query); // set the query:
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all of the rows:
    return $data;
}

$rows = readBlog($pdo);

//echo "<pre>" . print_r($rows, 1) . "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>PHP, PDO &amp; MySQL Tutorial</title>
        <!--
        I decided to make an external stylesheet to keep the code down. The stylesheet stays in the same folder
        as the other files. Feel free to use this file or create your own CSS.
        -->
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div id="heading" class="container">
            <h1>PHP, PDO and MySQL Tutorial</h1>
        </div>
        <div class="container bg-color">
            <form id="commentForm" action="" method="post">
                <fieldset>
                    <legend>Comment Form</legend>
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="" autofocus tabindex="1">
                    <label class="textBox" for="comment">Comment</label>
                    <textarea id="comment" name="comment" tabindex="2"></textarea>
                    <input type="submit" name="submit" value="submit" tabindex="3">
                </fieldset>
            </form>
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
