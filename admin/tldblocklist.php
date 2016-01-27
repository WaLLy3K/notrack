<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <link href="./css/master.css" rel="stylesheet" type="text/css" />
  <link rel="icon" type="image/png" href="./favicon.png" />
  <title>NoTrack TLD Blocklist</title>  
</head>

<body>
<div id="main">
<?php
$CurTopMenu = 'tldblocklist';
include('topmenu.html');
echo "<h1>Top Level Domain Blocklist</h1>\n";

$TLDBlockList = array();

$Mem = new Memcache;                             //Initiate Memcache
$Mem->connect('localhost');

//Load TLD Block List------------------------------------------------
function Load_TLDBlockList() {
//Blocklist is held in Memcache for 10 minutes
  global $TLDBlockList, $Mem;
  $TLDBlockList=$Mem->get('TLDBlockList');
  if (! $TLDBlockList) {    
    $FileHandle = fopen('/etc/notrack/domain-quick.list', 'r') or die('Error unable to open /etc/notrack/domain-quick.list');
    while (!feof($FileHandle)) {
      $TLDBlockList[] = trim(fgets($FileHandle));
    }
    fclose($FileHandle);
    $Mem->set('TLDBlockList', $TLDBlockList, 0, 600);
  }
  return null;
}

//Main---------------------------------------------------------------
Load_TLDBlockList();
asort($TLDBlockList);

//Draw Table---------------------------------------------------------
echo '<div class="row"><br />'."\n";
echo '<table id="block-table">'."\n";
echo '<tr><th>#</th><th>Site</th></tr>'."\n";
$i = 1;
foreach($TLDBlockList as $Site) {
  if ($Site != "") {
    echo '<tr><td>'.$i.'</td><td>'.$Site.'</td></tr>'."\n";
    $i++;
  }  
}
echo "</table></div>\n";
?> 
</div>
</body>
</html>