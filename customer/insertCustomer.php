<?php


    if (isset($_SERVER['HTTP_ORIGIN'])) {
        //header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');    
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
    }   
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
    //$additional_fields = $_POST['additionalFields'];
    //print_r($additional_fields['customer_site_state']);die;
    
    $business_unit = $_POST['business_unit'];
    //echo"<pre>";print_r($organizations);die;
    $errorDataArray = [];
    $successDataArray = [];
    foreach($business_unit as $company)
    {
        $value = $_POST['data'];
        $additional_fields = $_POST['additionalFields'];
        //$additional_value = $_POST['additional_data'];
        $company_name = $company['unit_description'];
        $name =  str_replace("&","&amp;",$value['customer_name']);
        $address1=  str_replace("&","&amp;",$value['customer_site_addr1']);
        $customer_gst=  str_replace("GSTIN:","",$_POST['customer_gst']);
        //echo $customer_gst;die;
        $xml = '<ENVELOPE>
            <HEADER>
            <TALLYREQUEST>IMPORT DATA</TALLYREQUEST>
            </HEADER>
            <BODY>
            <IMPORTDATA>
            <REQUESTDESC>
            <REPORTNAME>All Masters</REPORTNAME>
            <STATICVARIABLES>
            <SVCURRENTCOMPANY>'.$company_name.'</SVCURRENTCOMPANY>
            </STATICVARIABLES>
            </REQUESTDESC>
            <REQUESTDATA>
            <TALLYMESSAGE xmlns:UDF="TallyUDF">
            <LEDGER NAME="'.$name.'" RESERVEDNAME="">
                <ADDRESS.LIST TYPE="String">
                <ADDRESS>'.$address1.'</ADDRESS>
                <ADDRESS>'.$value['customer_site_city'].'</ADDRESS>
                </ADDRESS.LIST>
                <MAILINGNAME.LIST TYPE="String">
                <MAILINGNAME>'.$name.'</MAILINGNAME>
                </MAILINGNAME.LIST>
                <EMAIL>'.$value['contact_email'].'</EMAIL>
                <PRIORSTATENAME>'.$additional_fields['customer_site_state'].'</PRIORSTATENAME>
                <PINCODE>'.$value['customer_site_postcode'].'</PINCODE>
                <COUNTRYNAME>'.$value['country_name'].'</COUNTRYNAME>
                <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
                <VATDEALERTYPE>Regular</VATDEALERTYPE>
                <PARENT>Sundry Debtors</PARENT>
                <COUNTRYOFRESIDENCE>'.$value['country_name'].'</COUNTRYOFRESIDENCE>
                <LEDGERPHONE>'.$value['contact_phone'].'</LEDGERPHONE>
                <LEDGERCONTACT>'.$value['contact_fname'].' '.$value['contact_lname'].'</LEDGERCONTACT>
                <GSTTYPE/>
                <APPROPRIATEFOR/>
                <PARTYGSTIN>'.$customer_gst.'</PARTYGSTIN>
                <LEDSTATENAME>'.$additional_fields['customer_site_state'].'</LEDSTATENAME>
                <LANGUAGENAME.LIST>
                <NAME.LIST TYPE="String">
                <NAME>'.$name.'</NAME>
                </NAME.LIST>
                </LANGUAGENAME.LIST>
                </LEDGER>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                    <COMPANY>
                    <REMOTECMPINFO.LIST MERGE="Yes">
                    <REMOTECMPNAME>'.$company_name.'</REMOTECMPNAME>
                    
                    </REMOTECMPINFO.LIST>
                    </COMPANY>
                </TALLYMESSAGE>
                <TALLYMESSAGE xmlns:UDF="TallyUDF">
                    <COMPANY>
                    <REMOTECMPINFO.LIST MERGE="Yes">
                    <REMOTECMPNAME>'.$company_name.'</REMOTECMPNAME>
                    
                    </REMOTECMPINFO.LIST>
                    </COMPANY>
                </TALLYMESSAGE>
            </TALLYMESSAGE>
            </REQUESTDATA>
            </IMPORTDATA>
            </BODY>
            </ENVELOPE>';

            //echo $xml;die;

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_PORT => "9000",
            CURLOPT_URL => "http://localhost:9000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 500,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>$xml,
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            //echo $response; echo " | ";

            if ($err) {
       
                //print_r(json_encode(array('status' => '0','msg' =>$err)));
                array_push($dataArray,$err);
            } else {
                $xmlResponse = simplexml_load_string($response);
                $json = json_encode($xmlResponse);
                $decode = json_decode($json,true);
                
                //print_r($decode->LINEERROR);

                if(array_key_exists("LINEERROR",$decode))
                {
                    //echo "1";
                    //print_r($decode['LINEERROR']);
                    array_push($errorDataArray,'Error : '.$decode['LINEERROR']);
                    //print_r(json_encode(array('status' => '0','msg' =>$decode->LINEERROR)));
                }else{
                    //echo "2";
                    //print_r(json_encode(array('status' => '0','msg' =>"Not error")));
                    if($decode['CREATED']== '1' || $decode['ALTERED'] == '1' )
                    {
                        //echo "2";
                        //echo "<pre>";print_r($decode);
                        $value = array(
                            "name" => $name
                        );
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => "http://localhost/metricserp-tallyErp9/customer/fetchCustomer.php",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 500,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $value,
                        ));
                        $response1 = curl_exec($curl);
                        $err = curl_error($curl);
                        //print_r($response1);
                        curl_close($curl);
                        //echo $response1; echo" ";
                    
                            if ($err) {
                                //echo "cURL Error #:" . $err;
                                //print_r(json_encode(array('status' => '0','msg' =>$dataArray)));
                                array_push($errorDataArray,' Error : '.$err);
                            } else {
                                //print_r(json_encode(array('status' => '1','guid'=>$response1)));
                                array_push($successDataArray,$response1.'+'.$company['unit_id']);
                            }
                
                    }else {
                            
                        //print_r(json_encode(array('status' => '0','msg'=>'Something went wrong, please try sometimes later')));

                        array_push($errorDataArray,'Something went wrong, please try sometimes later');
                    }
                }

            }
                

    }

    // if (isset($successDataArray)) {

    //     print_r(json_encode(array('success'=>'1','status' => '1','msg' =>$successDataArray)));
    // }
     
    print_r(json_encode(array('status' => '1','error_msg' =>$errorDataArray, 'success_response' =>$successDataArray)));
  

?>