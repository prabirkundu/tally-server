

 <?php
  
  $dsn="TallyODBC64_9000";
  $user="";
  $password="";
    
   //storing connection id in $conn
  $conn=odbc_connect($dsn,$user, $password);
 
  //Checking connection id or reference
  if (!$conn)
   {
   echo (die(odbc_error()));
   }
   else
  {
      echo "Connection Successful !";
  }
  //Resource releasing
  odbc_close($conn);
  ?>
 
