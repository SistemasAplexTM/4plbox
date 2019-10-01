<?php

//SET THE LICENSE INFO
$license_owner = 'YOUR-LICENSE-OWNER-HERE';
$license_key = 'YOUR-LICNESE-KEY-HERE';

//DO NOT MODIFY THE FOLLOWING CODE
$uid = uniqid();
$license_hash = hash('sha256', $license_key . $uid, false);
$resp = $license_owner . '|' . $license_hash . '|' . $uid;

ob_start();
ob_clean();
header('Content-type: text/plain');
echo $resp;
ob_end_flush();
exit();
