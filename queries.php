<?php
    $conn = odbc_connect("TallyODBC64_9000","","");

    if ($conn) {

        //...Get all ledger's name frpm tally.....//
        // $Name = '$Name';
        // $query = odbc_exec($conn, "SELECT * FROM VOUCHERS");
        // while($row = odbc_fetch_array($query)){
        //   echo "<pre>";print_r($row);
        // };
        //$row = odbc_fetch_array($query);
        //print_r($row); 
        
        $Date = '$Date';
        $Reference = '$Reference';
        $VouchertypeName = '$VouchertypeName';
        $PartyLedgerName = '$PartyLedgerName';
        $CollectionField = '$Name';
        $query = odbc_exec($conn, "SELECT $Date:$Amount:1:LedgerEntries FROM RTSAllVouchers");
        while($row = odbc_fetch_array($query)){
          echo "<pre>";print_r($row);
        };
        
    }else{
        echo "connection lost";
    }
?>