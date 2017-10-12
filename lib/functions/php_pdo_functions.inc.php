<?php

/*
 * Insert Into Database Table myBlog.
 */

function createBlog(array $data, $pdo = NULL) {
    $query = 'INSERT INTO myBlog(title, comment, date_added) VALUES (:title, :comment, NOW())';
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([':title' => $data['title'], ':comment' => $data['comment']]);
    return $result;
}

/*
 * Read data from blog.
 */

function readBlog($pdo = NULL) {
    $query = 'SELECT id, title, comment,  DATE_FORMAT(date_added, "%W, %M %e, %Y") as display_date, date_added as my_date FROM myBlog ORDER BY my_date DESC';
    $stmt = $pdo->query($query); // Set the query:
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all the rows:
    return $data;
}

/*
 * Update record in database table.
 */

function updateBlog(array $data, $pdo = NULL) {
    $query = 'UPDATE myBlog SET title=:title, comment=:comment WHERE id =:id';
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([':title' => $data['title'], ':comment' => $data['comment'], ':id' => (int) $data['id']]);
    return $result;
}

/*
 * Delete record from database table.
 */

function deleteBlog($id = NULL, $pdo = NULL) {
    
}

function readUserBlog($id = NULL, $pdo = NULL) {
    $query = "SELECT id, title, comment FROM myBlog WHERE id=:id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
