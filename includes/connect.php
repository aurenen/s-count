<?php
/************************************************************
 *
 *  (c) s-count
 *  https://github.com/aurenen/s-count
 *  
 *  script: s-count
 *  file: includes/connect.php
 *  
 *  Handles all database connections.
 * 
 ************************************************************/

/**
 * Connects to MySQL database through PDO
 * @return PDO connection link
 */
function db_connect() {
    require_once 'config.php';

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';

    try {
        $link = new PDO($dsn, DB_USER, DB_PASS);
        $link->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);

        return $link;
    }
    catch (PDOException $ex) {
        echo 'Connection failed: ' . $ex->getMessage();
    }
}