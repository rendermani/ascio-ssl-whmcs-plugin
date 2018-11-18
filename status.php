<?php
require_once(realpath(dirname(__FILE__))."/../../../init.php");
require_once("lib/Ssl.php");

use Illuminate\Database\Capsule\Manager as Capsule;
use ascio\whmcs\ssl\Ssl;
use ascio\whmcs\ssl\Params;
use ascio\whmcs\ssl\Status;

// todo delete static service id
//$_POST["serviceId"] = 34;

$status = new Status( $_POST["serviceId"],true);
$status->setOrder();
$status->init();
$finished = $status->isFinished();
$status->setTitle("SSL Certificate: " .$status->getName());
$status->setExpireDate();
$html = $status->getStatusHtml() . $status->getInstructionsHtml();
$html = '<div class="panel-sidebar panel">'.$html.'</div>';
$params = new Params();
$params->serviceId = $_POST["serviceId"];
$ssl = new Ssl($params);
$sslData = $ssl->readDb();
$sans = $ssl->getSans();
if(count($sans) > 0) foreach($sans->data as $key => $san) {
    $data = array_merge($san,$sslData);
    $status->setData($data);
    $status->init();
    $status->setTitle("Additional Name (SAN): " .$san["name"]);
    if(!$finished) {
        $html .=  '<div class="panel-sidebar panel">'. $status->getStatusHtml() . $status->getInstructionsHtml().'</div>';
    }
    
}
echo json_encode(["status" => $html]);
?>