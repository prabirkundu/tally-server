<?php
    $conn = odbc_connect("TallyODBC64_9000","","");
    $array = $_POST['name'];
    //print_r();die;
    if ($conn) {
        $mailingName = '$MailingName';
        $query = odbc_exec($conn, "SELECT * FROM LEDGER WHERE `$mailingName` = '". $array."'");
        // while($row = odbc_fetch_array($query)){
        // echo "<pre>";print_r($row);
        // };
        $row = odbc_fetch_array($query);
        print_r($row['$Guid']);    
    }else{
        echo "connection lost";
    }

?>