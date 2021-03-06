<?php
$srcxml = simplexml_load_string($HTTP_RAW_POST_DATA);
$card = $srcxml->QRY['CardCode'];

$xml = simplexml_load_file('in.xml');
$findcard = false;

$funcname = 'GetCardImageEx';

foreach ($xml->children() as $child) {
    if (($child->getName() == $funcname) and ($child["CardCode"] == (string)$card)) {
        $imagesrc = 'images/' . $child["FileName"];
        $findcard = true;
        break;
    }
}

if (($findcard == true) and (file_exists($imagesrc))) {

    header('Content-Type: image/jpg');

    readfile($imagesrc);
    die;

} else $findcard = false;

if ($findcard == false) {
    $newxml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><Root></Root>');
    $newxml->addchild($funcname);
    $newxml->$funcname->addattribute('ErrorText', 'Card or image not found');
    header('Content-Type: text/xml');
    echo $newxml->asXML();
}

?>