
<?php
include("./cURL.php");
$BASE_URL = "https://www.transfermarkt.com";
if(isset($_GET["player_name"])){
    
    $teamName = $_GET["player_name"];
    $teamName = str_replace(" ","+", $teamName);

    
    $url = $BASE_URL."/schnellsuche/ergebnis/schnellsuche?query=".$teamName;
    $conn = Connect($url);

    preg_match_all('#<table class="items">(.*?)</table>#', $conn, $array);
    preg_match_all('#<td class="hauptlink">(.*?)</td>#', $array[0][0], $teamValue);
    
    $strposHREF = strpos($teamValue[0][0],'href="');
    $substrHREF = substr($teamValue[0][0], $strposHREF+6);
    $strLength = strlen($substrHREF);

    $x = strpos($substrHREF, '">');

    $final = substr($substrHREF, 0, $x);

    $goPlayerPage = Connect($BASE_URL.$final);
    //echo $goPlayerPage;
    
    $doc = new DOMDocument();
    $doc->loadHTML($goPlayerPage);
    $xpath = new DOMXPath($doc);
    
    $playerName = $xpath->query('//*[@id="main"]/main/header/div[1]/h1/strong/text()')->item(0);
    $teamValues = $xpath->query('//*[@id="main"]/main/header/div[6]/a/text()')->item(0);
    $currencyValue = $xpath->query('//*[@id="main"]/main/header/div[6]/a/span[1]/text()')->item(0);
    $currencyBig = $xpath->query('//*[@id="main"]/main/header/div[6]/a/span[2]/text()')->item(0);



    $data = array("playerName" => $playerName->textContent,'teamValue' => $teamValues->textContent, 'currencyValue' => $currencyValue->textContent, 'currencyBig' => $currencyBig->textContent);
    header('Content-type: text/javascript');
    echo json_encode($data);

}
    
?>

