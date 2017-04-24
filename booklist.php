<?php

function sanitize_input($string=""){
    $string = htmlspecialchars($string);
    $string = trim($string);
    $string = stripslashes($string);
}

function fetchQuery($db, $sql){
    try {
        $smt = $db->prepare($sql);
        $smt->execute();
        $rows = $smt->fetchAll();
        return $rows;
     } catch (PDOException $ex) {
        error_log($ex->getMessage(), 3, "errors.txt");
     }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' ){
    $db = new PDO("mysql:dbname=books;host=localhost", "root");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $type = sanitize_input($_REQUEST['type']);
    
    if($type == "list_category") {
        $sql = "SELECT * FROM category";
        $rows = fetchQuery($db, $sql);
        var_dump($rows);
    }
    if ($type === "in_category") {
        $category = strtolower($db->quote($_REQUEST['category']));
        $sql = "SELECT t.id, t.name, y.published, p.amount, c.name, a.name
            FROM title t JOIN year y ON y.id = t.id
            JOIN price p ON p.id = t.id
            JOIN category c ON c.book_id = t.id
            JOIN author a ON a.id = t.id WHERE c.name <> $category";
        $rows = fetchQuery($db, $sql);
        var_dump($rows);
    }
    
}
?>
