<?php
require_once 'config.php'; // Configuration file for turning error reporting and connection strings to database:
require_once 'php_pdo_functions.inc.php'; // PDO functions and connection:

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (isset($id)) {
    $data = readUserBlog($id, $pdo);
}

/*
 * Check to see if user has clicked on the submit button.
 */
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($submit) && $submit === "submit") {
    /*
     * Grab User's Edits from Form.
     */
    $data['id'] = htmlspecialchars($_POST['id']);
    $data['title'] = htmlspecialchars($_POST['title']);
    $data['comment'] = htmlspecialchars($_POST['comment']);
   
    $result = updateBlog($data, $pdo);
    
    if ($result) {
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Page</title>
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
                    <legend>Edit Content</legend>
                    <label for="title">Title</label>
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <input id="title" type="text" name="title" value="<?php echo $data['title']; ?>" autofocus tabindex="1">
                    <label class="textBox" for="comment">Comment</label>
                    <textarea id="comment" name="comment" tabindex="2"><?php echo $data['comment']; ?></textarea>
                    <input type="submit" name="submit" value="submit" tabindex="3">
                </fieldset>
            </form>
        </div>
    </body>
</html>