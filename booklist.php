<?php header('Content-type: text/xml');

function sanitize_input($string=""){
    $string = htmlspecialchars($string);
    $string = trim($string);
    $string = stripslashes($string);
    return $string;
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
    $type = "";
    if (isset($_POST['type']) ) {
        $type = sanitize_input($_POST['type']);
    }
    if($type == "list_category") {
        $sql = "SELECT * FROM category";
        $rows = fetchQuery($db, $sql);
        $output = '<? xml version="1.0" ?>';
        //$xml = new DOMDocument();
        foreach ($rows as $row) {
            /*$xml_category= $xml->createElement("Category");
            $xml_name = $xml->createElement("Name");
            $xml_id = $xml->createElement("Id");
            $xml_category->appendChild( $xml_name );
            $xml_category->appendChild($xml_id);
            $xml->appendChild( $xml_album );*/
            $output .= '<category>\n';
            $output .= '    <id>';
            $output .= $rows['book_id'];
            $output .= '</id>\n';
            $output .= '    <name>';
            $output .= $rows['name'];
            $output .= '</name>\n';
            $output .= '</category>';
        }
        //make the output pretty
        //$xml->formatOutput = true;

        //echo $xml->saveXML();
        print( $output);
    }
    if ($type === "in_category") {
        if (isset($_POST['type']) ) {
            $category = strtolower($db->quote($_POST['category']));
        }
        $sql = "SELECT t.id, t.name, y.published, p.amount, c.name, a.name
            FROM title t JOIN year y ON y.id = t.id
            JOIN price p ON p.id = t.id
            JOIN category c ON c.book_id = t.id
            JOIN author a ON a.id = t.id WHERE c.name <> $category";
        $rows = fetchQuery($db, $sql);
        
    }
    
}
?>
