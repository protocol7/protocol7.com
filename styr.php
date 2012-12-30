<html>
<head>
<link rel="stylesheet" href="http://twitter.github.com/bootstrap/assets/css/bootstrap-1.0.0.min.css">
<style>
table {
  width: auto;
}
</style>
<title>Cyklar</title>
</head>
<body>
<table>
<tr><th>Station</th><th>Lediga cyklar</th><th>Lediga platser</th></tr>
<?php

function ucname($string) {
    $string =ucwords(strtolower($string));

    foreach (array('-', '\'', '/') as $delimiter) {
      if (strpos($string, $delimiter)!==false) {
        $string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
      }
    }
    return $string;
}

$ctx = stream_context_create(array( 
    'http' => array( 
        'timeout' => 100 
        ) 
    ) 
);

$url='http://data.goteborg.se/StyrOchStall/v0.1/GetBikeStations/e2c65598-aaa8-4a56-8ca8-76013a3aa583?format=json';

if( false == ($str=file_get_contents($url, 0, $ctx )))
    echo "Could not read file.";

$json = json_decode($str,true);

$stations = $json["Stations"];

foreach ($stations as $k => $v) {
   print('<tr><td>' . ucname(mb_strtolower($v["Label"], "UTF-8")) . '</td><td>' . $v["FreeBikes"] . '</td><td>' . $v["FreeStands"] . '</td></tr>');
}
?>
</table>
</body>
</html>
