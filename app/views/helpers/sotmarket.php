<?
include_once($_SERVER['DOCUMENT_ROOT'] . '/public/dlinks/slibs/cKeysDb.php'); 
echo cKeysDb::getBlock($_SERVER['REQUEST_URI'],3);