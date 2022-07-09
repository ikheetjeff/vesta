<?php
// Init
error_reporting(NULL);
session_start();
include($_SERVER['DOCUMENT_ROOT']."/inc/main.php");

// Check token
if ((!isset($_GET['token'])) || ($_SESSION['token'] != $_GET['token'])) {
    header('Location: /login/');
    exit();
}

$v_domain = $_GET['domain'];
$v_domain = escapeshellarg($_GET['domain']);
if ($_GET['type'] == 'access') $type = 'access';
if ($_GET['type'] == 'error') $type = 'error';

header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$_GET['domain'].".".$type."-log.txt");
header("Content-Type: application/octet-stream; "); 
header("Content-Transfer-Encoding: binary");

$v_domain = escapeshellarg($_GET['domain']);
if ($_GET['type'] == 'access') $type = 'access';
if ($_GET['type'] == 'error') $type = 'error';



$cmd = implode(" ", array(
    escapeshellarg(VESTA_CMD . "v-list-web-domain-" . $type . "log"),
    escapeshellarg($user),
    escapeshellarg($v_domain),
    "5000",
));
passthru($cmd, $return_var);
if ($return_var != 0) {
    $errstr = "Internal server error: command returned non-zero: {$return_var}: {$cmd}";
    echo $errstr;
    throw new Exception($errstr); // make sure it ends up in an errorlog somewhere
}

