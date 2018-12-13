<?php
namespace ascio\whmcs\ssl;
use Illuminate\Database\Capsule\Manager as Capsule;
use Spatie\SslCertificate\SslCertificate;

require_once(__DIR__."/../v3/service/autoload.php");
require_once("Fqdn.php");
require_once("Dns.php");
require_once("Domain.php");
require_once("Error.php");
require_once("Params.php");
Class Status {
    private $serviceId; 
    /**
     * @var Fqdn $fqdn Full qualified domain name
     */
    private $fqdn;
    public $data; 
    private $messages = [];
    private $instructions; 
    public $type;
    public $dig = false;
    public $json = false;
    private $title;
    function __construct($serviceId,$useJson=false){
        if($useJson) {
            header('Content-Type: application/json');
            $this->json = true; 
        }
        $this->serviceId = $serviceId;
        $this->readDb();               
    }
    public function init() {
        switch($this->type) {
            case "File" : $this->setFile();
            case "Cname" : $this->setDns(); $this->setDig(); break;
            case "Txt" : $this->setDns(); $this->setDig(); break;
            case "Email" : $this->setEmail(); break;
        }
        $this->checkCertificate();
        $this->setSslLabs();
    }
    public function getStatusHtml() {
        $html = "";
        foreach($this->messages as $key => $message) {
            $html .= $message->getHtml();
        }
        return '<h4>'.$this->title.'</h4>'.$html;
    }
    public function getInstructionsHtml() {
        if($this->isFinished()) return;
        return $this->instructions->getHtml();
    }
    public function isFinished() {
        $finished = ["Completed","Failed","Order not validated","Invalid"];
        $order = $this->messages["order"]->status;
        if(in_array($order,$finished)) return true;
        if($this->messages["dig"]->status=="Completed") return true; 
        if($this->messages["file"]->status=="Completed") return true;
        return false;
    }
    private function readDb() {
        $data = Capsule::table('mod_asciossl')
        ->select(['message','status','errors','common_name','verification_type','approval_email','dns_name','dns_value','dns_error_code','dns_error_message','create_dns_record','dns_created',"expire_date"])
        ->where('whmcs_service_id',$this->serviceId)
        ->first();
        if($data->errors) {
            $data->errors = json_decode($data->errors);
        }
        $this->setData($data);        
    }
    public function getName() {
        return $this->data->common_name ? $this->data->common_name  : $this->data->name;
    }
    public function setData($data) {
        $this->data = (object) $data;
        $this->data->status = $this->data->status ? $this->data->status : $message;         
        $name = $data->common_name  ? $data->common_name : $data->name;
        $this->data->common_name = $name; 

        $this->fqdn = new Fqdn($name);
        // set the verification type
        if($this->data->verification_type == "Dns") {
            if($this->data->dns_name=="DNS TXT Record") {
                $this->type = "Txt";
            } else {
                $this->type = "Cname";
            }
        } else {
            $this->type = $this->data->verification_type;
        }
        // order instructions
        $this->messages = [];
        $this->instructions = new Instructions($this->type,$this->data);
    }
    public function setTitle($title) {
        $this->title = $title; 
    }
    public function setOrder() {
        $message = new StatusMessage("order");
        switch($this->data->status) {
            case "Completed" : $message->icon = "ok"; break;
            case "Failed" : $message->icon = "remove"; break;
            case "Invalid" : $message->icon = "remove"; break;
            case "Order not validated" : $message->icon = "remove"; break;
            default : $message->icon = "time"; break;
        } 
        if(strpos($this->data->status,"Pending")!==false) {
            $message->status = "Pending";
        } else {
            $message->status = $this->data->status;
        }

        $message->text = $this->data->status == "Pending_End_User_Action" ? "Pending SSL Verification" : $this->data->status;  
        if($message->icon =="remove") {
            $errors = $this->data->errors;
            $message->text = $this->data->status . ". " . join($errors,"<br/>");
        }
        $message->title ="Order Status"; 
        $this->messages["order"] = $message;
    }
    private function setDns() {
        if(!($this->type == "Cname" || $this->type == "Txt")) {
            return;
        }
        if($this->data->create_dns_record ==false) {
            return;
        }
        $message = new StatusMessage("createdns");
        if($this->data->dns_created) {
            $message->icon = "ok";
            $message->status = "Completed";
            $message->text = "DNS Created";
        }  elseif ($this->data->dns_error_message) {
            $message->status = "Failed";
            $message->icon = "info-sign";
            $message->text = $this->data->dns_error_message;
        } else {
            $message->icon = "time";
            $message->status = "Pending";
            $message->text = "Pending";
        } 
        $message->title = "Create DNS Record";
        $this->messages["createdns"] = $message;
    }
    private function setDig() {
        if(!($this->type == "Cname" || $this->type == "Txt")) {
            return;
        }
        if($this->type=="Cname") {
            $whatsmydnsUrl = "https://www.whatsmydns.net/#CNAME/".$this->data->dns_name."/".$this->data->dns_value;            
        } else {
            $whatsmydnsUrl = "https://www.whatsmydns.net/#TXT/_dnsauth.".$this->fqdn->getDomain()."/".$this->data->dns_value;   
        }
        $whatsmydnsLink = '<a target="dns" href="'.$whatsmydnsUrl.'">check WhatsMyDns.net</a>';
        $message = new StatusMessage("dig");
        $dns = new Dns(new Params(), $this->fqdn);
        $dig = $dns->digVerification($this->data->dns_name,$this->data->dns_value);
        if($dig) {
            $message->status = "Completed";
            $message->icon = "ok";
            $message->text = "Valid DNS-Record found ($whatsmydnsLink)";
        } else {
            $message->status = "Pending";
            $message->icon = "time";
            $message->text = "No valid DNS-Record found yet ($whatsmydnsLink)";
        }
        $message->title = "Dig for DNS record";
        $this->messages["dig"] = $message;
    }
    private function setFile() {
        if($this->type !== "File") {
            return;
        }
        $message = new StatusMessage("file");
        $content = file_get_contents("http://".$this->fqdn->getFqdn()."/".$this->data->dns_name); 
        if($content == $this->data->dns_value){
            $message->status = "Completed";
            $message->icon = "ok";
            $message->text = "Valid verification file found:". $this->data->dns_name;
        } else {
            $message->status = "Pending";
            $message->icon = "time";
            $message->text = "No valid verification-file found on your server.";
        }
        $message->title = "Looking for verification-file";
        $this->messages["file"] = $message;
    }
    private function setEmail() {
        if($this->type !== "Email") {
            return;
        }
        $message = new StatusMessage("Email");
        if($this->data->status=="Completed") {
            $message->status = "Completed";
            $message->icon = "ok";
            $message->text = "Email verified";
        } else {
            $message->status = "Pending";
            $message->icon = "time";
            $message->text = "Pending";
        }
        $message->title = "Email Verification";
        $this->messages["dig"] = $message; 
    }
    public function setExpireDate() {
        if(!($this->data->status == "Completed" )) {
            return;
        }
        $message = new StatusMessage("expire");
        $message->icon = "time";
        $message->status = "Completed";
        $message->text =date("jS F  Y",strtotime($this->data->expire_date." 00:00:01"));
        $message->title = "Expire Date";
        $this->messages["expire"] = $message;
    }
    private function setSslLabs() {
        if(!($this->data->status == "Completed" )) {
            return;
        }

        $message = new StatusMessage("ssllabs");
        $message->icon = "list-alt";
        $message->status = "Pending";
        $message->text = '<a target="ssllabs" href="https://www.ssllabs.com/ssltest/analyze.html?d='.$this->fqdn->getFqdn().'&hideResults=on&latest">Check on SSL Labs</a>';
        $message->title = "Detailed Certificate Test";
        $this->messages["ssllabs"] = $message;
    }
    private function checkCertificate() {
        if(!($this->data->status == "Completed" )) {
            return;
        }
        $message = new StatusMessage("checkcert");
        $message->title = "Quick Certificate Test"; 
        try {
            if($this->json) {
                $certificate = SslCertificate::createForHostName($this->fqdn->getFqdn());
                $message->icon = $certificate->isValid() ? "ok": "remove";
                $message->status = $certificate->isValid() ? "Completed" : "Not valid yet";
                $message->text =$certificate->isValid() ? "Valid" : "Invalid" ;                            
            } else {
                $message->icon = "time";
                $message->status =  "Pending";
                $message->text = "Pending";                 
            }
            

        } catch(\Exception $e) {
            $message->icon = "remove";
            $message->status =  "Pending";
            $message->text = $e->getMessage();            
        }

        
        $this->messages["checkcert"] = $message;     
    }
    
     
}
Class StatusMessage {
    public $type;
    public $icon;
    public $text;
    public $status;
    public $title;
    function __construct($type) {
        $this->type = $type;
    }
    public function getHtml() {
        if($this->status == "Order not validated") $status = "failed";
        else $status = $this->status;
        return '
            <div class="row '.$status.'">
                <div class="col-sm-4"  id="'.$this->type.'">    
                    <p><span class="glyphicon glyphicon-'.$this->icon.'"> </span> '.$this->title.'</p>
                </div>    
                <div class="col-sm-8"><p>'.$this->text.'</p></div>           
            </div>';
    }

}
Class Instructions {
    public $type;
    public $message;
    public $fields = [];
    public $data; 
    public function __construct($type,$data) {
        $this->type = $type;
        $this->data = $data;
        $this->fqdn = new Fqdn($data->common_name);
    }
    public function getHtml() {
        $this->get(); 
        if(!$this->message) return "";   
        $messages = '<div class="alert alert-success">'.$this->message.'</div>';
        foreach($this->fields as $key => $field) {
            foreach($field as $key => $value) {
                $messages .= '
                <div class="row">
                    <div class="col-sm-3">'.$key.'</div>    
                    <div class="col-sm-9">'.$value.'</div>   
                </div>';
            }           
         
        }
        return $messages; 
    }
    private function get() {
        switch($this->type) {
            case "Email" : return $this->setEmail(); break;
            case "File" : return $this->setFile(); break;
            case "Cname" : return $this->setCname(); break;
            case "Txt" : return $this->setTxt(); break;
        }
    }
    private function setTxt() {
        $this->message = 'Please add a <b>TXT</b>-Record to the zone: <b>'.$this->fqdn->getDomain().'</b>';
        $this->fields = [
            ["Source" => $this->fqdn->getSslAuth()],
            ["Target" => $this->data->dns_value]
        ];
    }
    private function setCname() {
        $this->message = 'Please add a <b>CNAME</b>-Record to the zone: <b>'.$this->fqdn->getDomain().'</b>';
        $this->fields = [
            ["Source" => $this->data->dns_name],
            ["Target" => $this->data->dns_value]
        ];        
    }
    private function setFile() {
        $url = 'http://'.$this->fqdn->getFqdn().'/'.$this->data->dns_name;
        $this->message = 'Please place this file your webspace: <b>'.$this->data->dns_name.'</b><br>Test: <a target="test" href="'.$url.'">'.$url.'</a>';
        $this->fields = [
            ["Location of the file" => $url],
            ["Content of the file" => $this->data->dns_value]
        ]; 
    }
    private function setEmail() {
        $this->message = 'Please confirm the E-Mail that was sent to: '.$this->data->approval_email;
    } 
    private function setEV() {
        // todo set EV messages
    }   
}