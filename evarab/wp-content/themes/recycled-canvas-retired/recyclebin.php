<?php
header("Content-type: text/xml");

require_once('../../../wp-config.php');
require_once('../../../wp-admin/admin-db.php');

global $wpdb;

$mouse_table = $wpdb->prefix . "recycle_data_mouse";
$element_table = $wpdb->prefix . "recycle_data_element";


$query = "SELECT * FROM " . $mouse_table . " ORDER BY 1 DESC";
$rows = $wpdb->get_results($query, ARRAY_A);

$xml_output = "<?xml version=\"1.0\"?>\n";

$xml_output .= "<mousetrackdata>\n";
$xml_output .= "<data_mouse>\n";

for($x = 0 ; $x < 250 ; $x++){
	$row = $rows[$x];
    $xml_output .= "\t<dataitem>\n";
    $xml_output .= "\t\t<session>" . $row['session'] . "</session>\n";
    $xml_output .= "\t\t<interval>" . $row['interval'] . "</interval>\n";
        $row['url'] = str_replace("&", "&amp;", $row['url']);
    $xml_output .= "\t\t<url>" . $row['url'] . "</url>\n";
    	$row['suburl'] = str_replace("&", "&amp;", $row['suburl']);
    $xml_output .= "\t\t<suburl>" . $row['suburl'] . "</suburl>\n";
    $xml_output .= "\t\t<coordx>" . $row['coordx'] . "</coordx>\n";
    $xml_output .= "\t\t<coordy>" . $row['coordy'] . "</coordy>\n";
    $xml_output .= "\t\t<activity>" . $row['activity'] . "</activity>\n";
    $xml_output .= "\t\t<time>" . $row['time'] . "</time>\n";
    $xml_output .= "\t\t<windowHeight>" . $row['windowHeight'] . "</windowHeight>\n";
    $xml_output .= "\t\t<windowWidth>" . $row['windowWidth'] . "</windowWidth>\n";
    $xml_output .= "\t\t<ip>" . $row['ip'] . "</ip>\n";
    $xml_output .= "\t\t<date>" . $row['date'] . "</date>\n";
    $xml_output .= "\t\t<label>" . $row['label'] . "</label>\n";
    $xml_output .= "\t\t<condition>" . $row['condition'] . "</condition>\n";


    $xml_output .= "\t</dataitem>\n";
}
$xml_output .= "</data_mouse>\n";



$query = "SELECT * FROM " . $element_table . " ORDER BY 1 DESC";
$rows = $wpdb->get_results($query, ARRAY_A);

$xml_output .= "<data_element>\n";

for($x = 0 ; $x < 250 ; $x++){
    $row = $rows[$x];
    $xml_output .= "\t<dataitem>\n";
    $xml_output .= "\t\t<session>" . $row['session'] . "</session>\n";
    $xml_output .= "\t\t<interval>" . $row['interval'] . "</interval>\n";
    $xml_output .= "\t\t<time>" . $row['time'] . "</time>\n";
    $xml_output .= "\t\t<id>" . $row['id'] . "</id>\n";
        $row['url'] = str_replace("&", "&amp;", $row['url']);
    $xml_output .= "\t\t<url>" . $row['url'] . "</url>\n";
    	$row['suburl'] = str_replace("&", "&amp;", $row['suburl']);
    $xml_output .= "\t\t<suburl>" . $row['suburl'] . "</suburl>\n";
    $xml_output .= "\t\t<type>" . $row['type'] . "</type>\n";
    $xml_output .= "\t\t<condition>" . $row['condition'] . "</condition>\n";


    $xml_output .= "\t</dataitem>\n";
}
$xml_output .= "</data_element>\n";

$xml_output .= "</mousetrackdata>";
echo $xml_output;

?>
