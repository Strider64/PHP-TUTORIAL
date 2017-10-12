<?php

function is_field_empty(array $fields) {
    /* Make sure user just didn't type spaces in an attempt to make valid */
    foreach ($fields as $key => $value) {
        $fields[$key] = isset($value) ? trim($value) : '';
    }
    /* If there is nothing a field then valid empty index is false */
    if (in_array("", $fields, true)) {
        return false;
    }
    /* return array */
    return $fields;
}

function is_name_unique(array $fields, $pdo = \NULL) {
    $query = "SELECT 1 FROM myUsers WHERE name = :name";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $fields['name']);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) {
        return false;
    } else {
        return $fields;
    }
}
