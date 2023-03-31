<?php
    $conn = odbc_connect("TallyODBC64_9000","","");
    if ($conn) {
        $mailingName = '$Guid';
        // $post = 'fb23cfa8-7cc6-44c7-8746-2da076e42f67-0000000a';
        $post = 'fb23cfa8-7cc6-44c7-8746-2da076e42f67-00000007';
        $query = odbc_exec($conn, "SELECT * FROM RTSAllVouchers");
        // while($row = odbc_fetch_array($query)){
        // echo "<pre>";print_r($row);
        // };
        $row = odbc_fetch_array($query);
        print_r($row);    
    }else{
        echo 1;
    }

?>