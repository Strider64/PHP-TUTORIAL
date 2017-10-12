<?php

function createLogin(array $data, $pdo) {
    /* Secure the Password by hashing the user's password. */
    $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, array("cost" => 15));
    try {

        /* Set the query variable */
        $query = 'INSERT INTO myUsers (name, password, email, security, confirmation, date_added) VALUES (:name, :password, :email, :security, :confirmation, NOW())';

        /* Prepare the query */
        $stmt = $pdo->prepare($query);

        /* Execute the query with the stored prepared values */
        $result = $stmt->execute([
            ':name' => $data['name'],
            ':password' => $data['password'],
            ':email' => $data['email'],
            ':security' => $data['security'],
            ':confirmation' => $data['confirmation']
        ]); // End of execution:
        return TRUE;
    } catch (PDOException $error) {
        // Check to see if name is already exists:
        $errorCode = $error->errorInfo[1];
        if ($errorCode == MYSQL_ERROR_DUPLICATE_ENTRY) {
            error_log("Duplicate Name was Enter", 1, "jrpepp@pepster.com");
        } else {
            throw $error;
        }
    }
}

function readLogin(array $data, $pdo) {
            /* Setup the Query for reading in login data from database table */
        $query = 'SELECT id, name, password, email, security  FROM myUsers WHERE name=:name';


        $stmt = $pdo->prepare($query); // Prepare the query:
        $stmt->execute([':name' => $data['name']]); // Execute the query with the supplied user's parameter(s):

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();
        if(!$user) {
            return FALSE;
        }
        /*
         * If password matches database table match send back true otherwise send back false.
         */
        if (password_verify($data['password'], $user['password'])) {
            unset($user['password']);
            return $user;
        } else {
            return \FALSE;
        }
}

function updateLogin(array $data, $pdo) {
    
}

function deleteLogin($id = \NULL, $pdo) {
    
}
