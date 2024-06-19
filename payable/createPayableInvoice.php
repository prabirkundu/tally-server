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

$value = $_POST;
//echo "<pre>";print_r($value);die;
$date=date_create($value['invoice_date']);
$invoice_date = date_format($date,"Ymd");
$name =  str_replace("&","&amp;",$value['vendor_name']);
$address1=  str_replace("&","&amp;",$value['vendor_addr1']);
$vendor_gst=  str_replace("GSTIN:","",$_POST['vendor_gst']);
$total = $value['total_amount'];

$xmllinesTax = '';


foreach($value['invoice_tax'] as $linesTax)
{
    $gst = $linesTax['tax_code'];
    $xmllinesTax.= 
    "<LEDGERENTRIES.LIST>
        <ADDLALLOCTYPE/>
        <ROUNDTYPE/>
        <NARRATION>Narration</NARRATION>
        <LEDGERNAME>".$gst."</LEDGERNAME>
        <VOUCHERFBTCATEGORY/>
        <GSTCLASS/>
        <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
        <LEDGERFROMITEM>No</LEDGERFROMITEM>
        <ISPARTYLEDGER>No</ISPARTYLEDGER>
        <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
        <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
        <AMOUNT>-".number_format($linesTax['tax_value'],2,'.',',')."</AMOUNT>
        <VATEXPAMOUNT>-".number_format($linesTax['tax_value'],2,'.',',')."</VATEXPAMOUNT>
        <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
        <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
        <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
        <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
        <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
        <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
        <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
        <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
        <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
        <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
        <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
        <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
        <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
        <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
        <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
        <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
        <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
        <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
        <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
        <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
        <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
        <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
        <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
    </LEDGERENTRIES.LIST>";
}

$xmllines = '';

foreach($value['invoice_lines'] as $lines)
{
    //$quentity = floatval($lines['line_quantity']); 
    $quentity = $lines['line_quantity'];
    $line_description=  str_replace("&","&amp;",$lines['line_description']);

    $xmllines.=
    "<ALLINVENTORYENTRIES.LIST>
       <BASICUSERDESCRIPTION.LIST TYPE='String'>
       <BASICUSERDESCRIPTION>".$line_description."</BASICUSERDESCRIPTION>
       </BASICUSERDESCRIPTION.LIST>
        <STOCKITEMNAME>Item</STOCKITEMNAME>
        <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
        <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
        <ISAUTONEGATE>No</ISAUTONEGATE>
        <ISSCRAP>No</ISSCRAP>
        <RATE>".number_format($lines['unit_price'],2,'.',',')."</RATE>
        <DISCOUNT> ".$lines['discount_percentage']."</DISCOUNT>
        <AMOUNT>-".number_format($lines['line_amount'],2,'.',',')."</AMOUNT>
        <ACTUALQTY>".$lines['line_quantity']."</ACTUALQTY>
        <BILLEDQTY> ".$lines['line_quantity']."</BILLEDQTY>
        <ISINCLTAXRATEFIELDEDITED>No</ISINCLTAXRATEFIELDEDITED>
        <TEMPRATE>".number_format($lines['unit_price'],2,'.',',')."</TEMPRATE>
        <BATCHALLOCATIONS.LIST>       </BATCHALLOCATIONS.LIST>
        <ACCOUNTINGALLOCATIONS.LIST>
        <NARRATION>Narration</NARRATION>
        <LEDGERNAME>".$lines['account_code_description']."</LEDGERNAME>
        <GSTCLASS/>
        <ISDEEMEDPOSITIVE>Yes</ISDEEMEDPOSITIVE>
        <LEDGERFROMITEM>No</LEDGERFROMITEM>
        <ISPARTYLEDGER>No</ISPARTYLEDGER>
        <ISLASTDEEMEDPOSITIVE>Yes</ISLASTDEEMEDPOSITIVE>
        <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED> 
        <AMOUNT>-".number_format($lines['line_amount'],2,'.',',')."</AMOUNT>
        <SERVICETAXDETAILS.LIST>        </SERVICETAXDETAILS.LIST>
        <BANKALLOCATIONS.LIST>        </BANKALLOCATIONS.LIST>
        <BILLALLOCATIONS.LIST>        </BILLALLOCATIONS.LIST>
        <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
        <OLDAUDITENTRIES.LIST>        </OLDAUDITENTRIES.LIST>
        <ACCOUNTAUDITENTRIES.LIST>        </ACCOUNTAUDITENTRIES.LIST>
        <AUDITENTRIES.LIST>        </AUDITENTRIES.LIST>
        <INPUTCRALLOCS.LIST>        </INPUTCRALLOCS.LIST>
        <DUTYHEADDETAILS.LIST>        </DUTYHEADDETAILS.LIST>
        <EXCISEDUTYHEADDETAILS.LIST>        </EXCISEDUTYHEADDETAILS.LIST>
        <RATEDETAILS.LIST>        </RATEDETAILS.LIST>
        <SUMMARYALLOCS.LIST>        </SUMMARYALLOCS.LIST>
        <STPYMTDETAILS.LIST>        </STPYMTDETAILS.LIST>
        <EXCISEPAYMENTALLOCATIONS.LIST>        </EXCISEPAYMENTALLOCATIONS.LIST>
        <TAXBILLALLOCATIONS.LIST>        </TAXBILLALLOCATIONS.LIST>
        <TAXOBJECTALLOCATIONS.LIST>        </TAXOBJECTALLOCATIONS.LIST>
        <TDSEXPENSEALLOCATIONS.LIST>        </TDSEXPENSEALLOCATIONS.LIST>
        <VATSTATUTORYDETAILS.LIST>        </VATSTATUTORYDETAILS.LIST>
        <REFVOUCHERDETAILS.LIST>        </REFVOUCHERDETAILS.LIST>
        <INVOICEWISEDETAILS.LIST>        </INVOICEWISEDETAILS.LIST>
        <VATITCDETAILS.LIST>        </VATITCDETAILS.LIST>
        <ADVANCETAXDETAILS.LIST>        </ADVANCETAXDETAILS.LIST>
        </ACCOUNTINGALLOCATIONS.LIST>
        <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
        <SUPPLEMENTARYDUTYHEADDETAILS.LIST>       </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
        <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
        <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
        <EXCISEALLOCATIONS.LIST>       </EXCISEALLOCATIONS.LIST>
        <EXPENSEALLOCATIONS.LIST>       </EXPENSEALLOCATIONS.LIST>
   </ALLINVENTORYENTRIES.LIST>";
}


$xmlTds = '';
if($value['is_tds_applicable'] == 1){

     $total = $total-$value['tds_amount'];
     $xmlTds =  "<LEDGERENTRIES.LIST>
          <LEDGERNAME>".$value['tds_code']."</LEDGERNAME>
          <GSTCLASS/>
          <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
          <LEDGERFROMITEM>No</LEDGERFROMITEM>
          <REMOVEZEROENTRIES>No</REMOVEZEROENTRIES>
          <ISPARTYLEDGER>No</ISPARTYLEDGER>
          <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
          <ISCAPVATTAXALTERED>No</ISCAPVATTAXALTERED>
          <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
          <AMOUNT>".number_format($value['tds_amount'],2,'.',',')."</AMOUNT>
          <VATEXPAMOUNT>".number_format($value['tds_amount'],2,'.',',')."</VATEXPAMOUNT>
          <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
          <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
          <BILLALLOCATIONS.LIST>       </BILLALLOCATIONS.LIST>
          <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
          <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
          <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
          <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
          <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
          <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
          <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
          <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
          <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
          <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
          <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
          <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
          <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
          <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
          <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
          <COSTTRACKALLOCATIONS.LIST>       </COSTTRACKALLOCATIONS.LIST>
          <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
          <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
          <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
          <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
          </LEDGERENTRIES.LIST>";

}

$xml = '';
$xml.= '<ENVELOPE>
<HEADER>
 <TALLYREQUEST>Import Data</TALLYREQUEST>
</HEADER>
<BODY>
 <IMPORTDATA>
  <REQUESTDESC>
   <REPORTNAME>Vouchers</REPORTNAME>
   <STATICVARIABLES>
    <SVCURRENTCOMPANY>Metrics Business Systems 2</SVCURRENTCOMPANY>
   </STATICVARIABLES>
  </REQUESTDESC>
  <REQUESTDATA>
   <TALLYMESSAGE xmlns:UDF="TallyUDF">
    <VOUCHER VCHTYPE="Purchase" ACTION="Create" OBJVIEW="Invoice Voucher View">
     <ADDRESS.LIST TYPE="String">
      <ADDRESS>'.$address1.'</ADDRESS>
      <ADDRESS>'.$value['vendor_city'].'</ADDRESS>
     </ADDRESS.LIST>
     <DATE>20230401</DATE>
     <REFERENCEDATE>20230401</REFERENCEDATE>
     <BILLOFLADINGDATE></BILLOFLADINGDATE>
     <GSTREGISTRATIONTYPE>Regular</GSTREGISTRATIONTYPE>
     <VATDEALERTYPE>Regular</VATDEALERTYPE>
     <STATENAME>'.$value['vendor_state'].'</STATENAME>
     <VOUCHERTYPENAME>Purchase</VOUCHERTYPENAME>
     <PARTYGSTIN>'.$vendor_gst.'</PARTYGSTIN>
     <COUNTRYOFRESIDENCE>'.$value['vendor_country'].'</COUNTRYOFRESIDENCE>
     <PLACEOFSUPPLY>'.$value['vendor_state'].'</PLACEOFSUPPLY>
     <CLASSNAME/>
     <PARTYNAME>'.$name.'</PARTYNAME>
     <PARTYLEDGERNAME>'.$name.'</PARTYLEDGERNAME>
     <BUYERADDRESSTYPE/>
     <REFERENCE>'.$value['vendor_invoice_number'].'</REFERENCE>
     <PARTYMAILINGNAME>'.$name.'</PARTYMAILINGNAME>
     <PARTYPINCODE>'.$value['vendor_postcode'].'</PARTYPINCODE>
     <CONSIGNEEMAILINGNAME>'.$name.'</CONSIGNEEMAILINGNAME>
     <CONSIGNEESTATENAME>'.$value['vendor_state'].'</CONSIGNEESTATENAME>
     <VOUCHERNUMBER>'.$value['invoice_code'].'</VOUCHERNUMBER>
     <BASICBASEPARTYNAME>'.$name.'</BASICBASEPARTYNAME>
     <CSTFORMISSUETYPE/>
     <CSTFORMRECVTYPE/>
     <PERSISTEDVIEW>Invoice Voucher View</PERSISTEDVIEW>
     <PARTYADDRESSTYPE/>
     <CONSIGNEECOUNTRYNAME>'.$value['vendor_country'].'</CONSIGNEECOUNTRYNAME>
     <VCHGSTCLASS/>
     <VCHENTRYMODE>Item Invoice</VCHENTRYMODE>
     <DIFFACTUALQTY>No</DIFFACTUALQTY>
     <ISDELETED>No</ISDELETED>
     <ASORIGINAL>No</ASORIGINAL>
     <FORJOBCOSTING>No</FORJOBCOSTING>
     <ISOPTIONAL>No</ISOPTIONAL>
     <USEFOREXCISE>No</USEFOREXCISE>
     <USEFORINTEREST>No</USEFORINTEREST>
     <USEFORGAINLOSS>No</USEFORGAINLOSS>
     <USEFORGODOWNTRANSFER>No</USEFORGODOWNTRANSFER>
     <USEFORCOMPOUND>No</USEFORCOMPOUND>
     <USEFORSERVICETAX>No</USEFORSERVICETAX>
     <ISONHOLD>No</ISONHOLD>
     <EXCISETAXOVERRIDE>No</EXCISETAXOVERRIDE>
     <IGNOREPOSVALIDATION>No</IGNOREPOSVALIDATION>
     <ISTDSOVERRIDDEN>No</ISTDSOVERRIDDEN>
     <ISTCSOVERRIDDEN>No</ISTCSOVERRIDDEN>
     <ISVATOVERRIDDEN>No</ISVATOVERRIDDEN>
     <ISSERVICETAXOVERRIDDEN>No</ISSERVICETAXOVERRIDDEN>
     <ISEXCISEOVERRIDDEN>No</ISEXCISEOVERRIDDEN>
     <ISGSTOVERRIDDEN>No</ISGSTOVERRIDDEN>
     <IGNOREGSTINVALIDATION>No</IGNOREGSTINVALIDATION>
     <IGNOREEINVVALIDATION>No</IGNOREEINVVALIDATION>
     <ISCANCELLED>No</ISCANCELLED>
     <HASCASHFLOW>No</HASCASHFLOW>
     <ISPOSTDATED>No</ISPOSTDATED>
     <USETRACKINGNUMBER>No</USETRACKINGNUMBER>
     <ISINVOICE>Yes</ISINVOICE>
     <MFGJOURNAL>No</MFGJOURNAL>
     <HASDISCOUNTS>No</HASDISCOUNTS>
     <ASPAYSLIP>No</ASPAYSLIP>
     <ISCOSTCENTRE>No</ISCOSTCENTRE>
     <ISSTXNONREALIZEDVCH>No</ISSTXNONREALIZEDVCH>
     <ISBLANKCHEQUE>No</ISBLANKCHEQUE>
     <ISVOID>No</ISVOID>
     <ORDERLINESTATUS>No</ORDERLINESTATUS>
     <ISVATDUTYPAID>Yes</ISVATDUTYPAID>
     <ISDELETEDVCHRETAINED>No</ISDELETEDVCHRETAINED>
     <CURRPARTYLEDGERNAME>'.$name.'</CURRPARTYLEDGERNAME>
     <CURRBASICBUYERNAME>'.$name.'</CURRBASICBUYERNAME>
     <CURRPARTYNAME>'.$name.'</CURRPARTYNAME>
     <CURRBUYERADDRESSTYPE/>
     <CURRPARTYADDRESSTYPE/>
     <CURRSTATENAME>'.$value['vendor_state'].'</CURRSTATENAME>
     <CURRBASICSHIPDELIVERYNOTE/>
     <TEMPGSTVCHDESTINATIONSTATE>'.$value['vendor_state'].'</TEMPGSTVCHDESTINATIONSTATE>
     <TEMPGSTPARTYDEALERTYPE>Regular</TEMPGSTPARTYDEALERTYPE>
     <EWAYBILLDETAILS.LIST>      </EWAYBILLDETAILS.LIST>
     <EXCLUDEDTAXATIONS.LIST>      </EXCLUDEDTAXATIONS.LIST>
     <OLDAUDITENTRIES.LIST>      </OLDAUDITENTRIES.LIST>
     <ACCOUNTAUDITENTRIES.LIST>      </ACCOUNTAUDITENTRIES.LIST>
     <AUDITENTRIES.LIST>      </AUDITENTRIES.LIST>
     <DUTYHEADDETAILS.LIST>      </DUTYHEADDETAILS.LIST>
     '.$xmllines.'
     <SUPPLEMENTARYDUTYHEADDETAILS.LIST>      </SUPPLEMENTARYDUTYHEADDETAILS.LIST>
     <EWAYBILLERRORLIST.LIST>      </EWAYBILLERRORLIST.LIST>
     <IRNERRORLIST.LIST>      </IRNERRORLIST.LIST>
     <INVOICEDELNOTES.LIST>
      <BASICSHIPPINGDATE></BASICSHIPPINGDATE>
      <BASICSHIPDELIVERYNOTE></BASICSHIPDELIVERYNOTE>
     </INVOICEDELNOTES.LIST>
     <INVOICEORDERLIST.LIST>      </INVOICEORDERLIST.LIST>
     <INVOICEINDENTLIST.LIST>      </INVOICEINDENTLIST.LIST>
     <ATTENDANCEENTRIES.LIST>      </ATTENDANCEENTRIES.LIST>
     <ORIGINVOICEDETAILS.LIST>      </ORIGINVOICEDETAILS.LIST>
     <INVOICEEXPORTLIST.LIST>      </INVOICEEXPORTLIST.LIST>
     <LEDGERENTRIES.LIST>
     <NARRATION>Narration provided</NARRATION>
      <LEDGERNAME>'.$name.'</LEDGERNAME>
      <GSTCLASS/>
      <ISDEEMEDPOSITIVE>No</ISDEEMEDPOSITIVE>
      <LEDGERFROMITEM>No</LEDGERFROMITEM>
      <ISPARTYLEDGER>No</ISPARTYLEDGER>
      <ISLASTDEEMEDPOSITIVE>No</ISLASTDEEMEDPOSITIVE>
      <ISCAPVATNOTCLAIMED>No</ISCAPVATNOTCLAIMED>
      <AMOUNT>'.number_format($total,2,'.',',').'</AMOUNT>
      <SERVICETAXDETAILS.LIST>       </SERVICETAXDETAILS.LIST>
      <BANKALLOCATIONS.LIST>       </BANKALLOCATIONS.LIST>
      <BILLALLOCATIONS.LIST>
       <NAME>'.$value['invoice_code'].'</NAME>
       <BILLTYPE>New Ref</BILLTYPE>
       <TDSDEDUCTEEISSPECIALRATE>No</TDSDEDUCTEEISSPECIALRATE>
       <AMOUNT>'.number_format($total,2,'.',',').'</AMOUNT>
       <INTERESTCOLLECTION.LIST>        </INTERESTCOLLECTION.LIST>
       <STBILLCATEGORIES.LIST>        </STBILLCATEGORIES.LIST>
      </BILLALLOCATIONS.LIST>
      <INTERESTCOLLECTION.LIST>       </INTERESTCOLLECTION.LIST>
      <OLDAUDITENTRIES.LIST>       </OLDAUDITENTRIES.LIST>
      <ACCOUNTAUDITENTRIES.LIST>       </ACCOUNTAUDITENTRIES.LIST>
      <AUDITENTRIES.LIST>       </AUDITENTRIES.LIST>
      <INPUTCRALLOCS.LIST>       </INPUTCRALLOCS.LIST>
      <INVENTORYALLOCATIONS.LIST>       </INVENTORYALLOCATIONS.LIST>
      <DUTYHEADDETAILS.LIST>       </DUTYHEADDETAILS.LIST>
      <EXCISEDUTYHEADDETAILS.LIST>       </EXCISEDUTYHEADDETAILS.LIST>
      <RATEDETAILS.LIST>       </RATEDETAILS.LIST>
      <SUMMARYALLOCS.LIST>       </SUMMARYALLOCS.LIST>
      <STPYMTDETAILS.LIST>       </STPYMTDETAILS.LIST>
      <EXCISEPAYMENTALLOCATIONS.LIST>       </EXCISEPAYMENTALLOCATIONS.LIST>
      <TAXBILLALLOCATIONS.LIST>       </TAXBILLALLOCATIONS.LIST>
      <TAXOBJECTALLOCATIONS.LIST>       </TAXOBJECTALLOCATIONS.LIST>
      <TDSEXPENSEALLOCATIONS.LIST>       </TDSEXPENSEALLOCATIONS.LIST>
      <VATSTATUTORYDETAILS.LIST>       </VATSTATUTORYDETAILS.LIST>
      <REFVOUCHERDETAILS.LIST>       </REFVOUCHERDETAILS.LIST>
      <INVOICEWISEDETAILS.LIST>       </INVOICEWISEDETAILS.LIST>
      <VATITCDETAILS.LIST>       </VATITCDETAILS.LIST>
      <ADVANCETAXDETAILS.LIST>       </ADVANCETAXDETAILS.LIST>
     </LEDGERENTRIES.LIST>
     '.$xmllinesTax.'
     '.$xmlTds.'
     <VCHLEDTOTALTREE.LIST>      </VCHLEDTOTALTREE.LIST>
     <PAYROLLMODEOFPAYMENT.LIST>      </PAYROLLMODEOFPAYMENT.LIST>
     <ATTDRECORDS.LIST>      </ATTDRECORDS.LIST>
     <GSTEWAYCONSIGNORADDRESS.LIST>      </GSTEWAYCONSIGNORADDRESS.LIST>
     <GSTEWAYCONSIGNEEADDRESS.LIST>      </GSTEWAYCONSIGNEEADDRESS.LIST>
     <TEMPGSTRATEDETAILS.LIST>      </TEMPGSTRATEDETAILS.LIST>
    </VOUCHER>
   </TALLYMESSAGE>
   <TALLYMESSAGE xmlns:UDF="TallyUDF">
    <COMPANY>
     <REMOTECMPINFO.LIST MERGE="Yes">
      <REMOTECMPNAME>Metrics Business Systems 2</REMOTECMPNAME>
     </REMOTECMPINFO.LIST>
    </COMPANY>
   </TALLYMESSAGE>
   <TALLYMESSAGE xmlns:UDF="TallyUDF">
    <COMPANY>
     <REMOTECMPINFO.LIST MERGE="Yes">
      <REMOTECMPNAME>Metrics Business Systems 2</REMOTECMPNAME>
     </REMOTECMPINFO.LIST>
    </COMPANY>
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
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>$xml,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    echo $response;die;
    
    if ($err) {
      //echo "cURL Error #:" . $err;
      print_r(json_encode(array('status' => '0','msg' =>$err)));
    } else {
      $xmlResponse = simplexml_load_string($response);
      $json = json_encode($xmlResponse);
      $decode = json_decode($json,true);

      //print_r($decode['LINEERROR']);die;

      if(array_key_exists("LINEERROR",$decode))
      {
        //array_push($errorDataArray,'Error : '.$decode['LINEERROR']);
        print_r(json_encode(array('status' => '0','msg'=>$decode['LINEERROR'])));

      }else {

         if($decode['CREATED'] == '1'|| $decode['ALTERED'] == '1' )
         {
 
           print_r(json_encode(array('status' => '1','guid'=>'1'.'+'.$value['unit_id'].'+'.$value['organization_id'])));
         }elseif($decode['ERRORS'] == '1') {
 
           print_r(json_encode(array('status' => '0','msg'=>$decode['LINEERROR'])));
         }else {
           
           print_r(json_encode(array('status' => '0','msg'=>'Something went wrong, please try sometimes later')));
         }

      }
       
        
    }
  
?>