<?php 
header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');

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
        $rows = $smt->fetchAll(PDO::FETCH_ASSOC);
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
        $json = sanitize_input($_POST['json']);
    }
    if($type == "list_category") {
        $sql = "SELECT * FROM category";
        $rows = fetchQuery($db, $sql);
        if ($json == "false") {
            $output = '<? xml version="1.0" ?>';
            $xml = new SimpleXMLElement('<xml/>');
            foreach ($rows as $row) {
                /*$xml_category= $xml->createElement("Category");
                $xml_name = $xml->createElement("Name");
                $xml_id = $xml->createElement("Id");
                $xml_category->appendChild( $xml_name );
                $xml_category->appendChild($xml_id);
                $xml->appendChild( $xml_album );*/
                $output .= '<category>\n';
                $output .= '<id>';
                $output .= $row['book_id'];
                $output .= '</id>\n';
                $output .= '<name>';
                $output .= $row['name'];
                $output .= '</name>\n';
                $output .= '</category>';
        }
        } else { //send as json
            $output = json_encode($rows);
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
        $sql = "SELECT t.id, t.name, y.published, p.amount, c.name, a.author_name
            FROM title t JOIN year y ON y.id = t.id
            JOIN price p ON p.id = t.id
            JOIN category c ON c.book_id = t.id
            JOIN author a ON a.id = t.id WHERE c.name <> $category";
        $rows = fetchQuery($db, $sql);
        if ($json == "false") {
            $output = '<? xml version="1.0" ?>';
        } else { //json output
            $output = json_encode($rows);
        }
        print( $output);
        
    }
    
}
?>
