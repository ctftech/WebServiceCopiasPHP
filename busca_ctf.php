<?php

header('Content-Type: text/html; charset=utf-8"');

$ponteiro = $_REQUEST['pont'];
if(!is_numeric($ponteiro))$ponteiro = 0;


$xml = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <SoapLogin xmlns="http://tempuri.org/">
      <login>LOGIN</login>
      <senha>SENHA</senha>
    </SoapLogin>
  </soap:Header>
  <soap:Body>
    <RecuperarCopia xmlns="http://tempuri.org/">
      <parametroCopia>
        <Ponteiro>'.$ponteiro.'</Ponteiro>
        <CodTemplate>21</CodTemplate>
        <QtdRegistro>8000</QtdRegistro>
      </parametroCopia>
    </RecuperarCopia>
  </soap:Body>
</soap:Envelope>';
$url = "http://www.portalctf.com.br/portalcopias/wscopia.asmx";


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml")); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TRANSFERTEXT, true);
curl_setopt($ch, CURLOPT_CRLF, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml );

//execute post
$data = curl_exec($ch);
//file_put_contents("baixa_".date("d_m_Y_H_i_s")."_ctf.xml",html_entity_decode($data));
$xml = simplexml_load_string(html_entity_decode($data));

foreach($xml->xpath('//soap:Body') as $header) {
   $header->registerXPathNamespace('default', 'http://tempuri.org/');
   $header->xpath('//default:RecuperarCopiaResult');
}


$obj = $header->RecuperarCopiaResponse->RecuperarCopiaResult->Abastecimentos;

echo "Ponteiro informado: ".$ponteiro."<br>";
echo "Ponteiro do CTF: ".$obj->attributes()."<br>";

echo"<pre>";
for($x=0;$x<count($obj->AbastecimentosRow);$x++)
{
	print_r($obj->AbastecimentosRow[$x]);
	echo"<br>-------------------------<br>";
}
echo"</pre>";

?>

