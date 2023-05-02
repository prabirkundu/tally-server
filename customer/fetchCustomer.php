<?php
    $conn = odbc_connect("TallyODBC64_9000","","");
    $array = $_POST['name'];
    //print_r();die;
    if ($conn) {
        $mailingName = '$MailingName';

        try {
            $query = odbc_exec($conn, "SELECT * FROM LEDGER WHERE `$mailingName` = '". $array."'");
            $row = odbc_fetch_array($query);
            $result = $row['$Guid'];
            print_r($result);   
        }catch(Exception $e) {
            echo '1';
        }
        //print_r($result);    
    }else{
        echo "connection lost";
    }

    // while($row = odbc_fetch_array($query)){
    // echo "<pre>";print_r($row);
    // };

?>