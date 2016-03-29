<?php
require("ote/autoload.php");
use ascio\v3 as ascio;
/**
 * WHMCS SDK Sample Provisioning Module
 *
 * Provisioning Modules, also referred to as Product or Server Modules, allow
 * you to create modules that allow for the provisioning and management of
 * products and services in WHMCS.
 *
 * This sample file demonstrates how a provisioning module for WHMCS should be
 * structured and exercises all supported functionality.
 *
 * Provisioning Modules are stored in the /modules/servers/ directory. The
 * module name you choose must be unique, and should be all lowercase,
 * containing only letters & numbers, always starting with a letter.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "asciossl" and therefore all
 * functions begin "asciossl_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _ConfigOptions
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see http://docs.whmcs.com/Provisioning_Module_Developer_Docs
 *
 * @copyright Copyright (c) WHMCS Limited 2015
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

// Require any libraries needed for the module to function.
// require_once __DIR__ . '/path/to/library/loader.php';
//
// Also, perform any initialization required by the service's library.

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related abilities and
 * settings.
 *
 * @see http://docs.whmcs.com/Provisioning_Module_Meta_Data_Parameters
 *
 * @return array
 */
function asciossl_MetaData()
{
    return array(
        'DisplayName' => 'Ascio SSL module',
        'APIVersion' => '1.1', // Use API Version 1.1
        'RequiresServer' => false, // Set true if module requires a server to work
        'DefaultNonSSLPort' => '1111', // Default Non-SSL Connection Port
        'DefaultSSLPort' => '1112', // Default SSL Connection Port
        'ServiceSingleSignOnLabel' => 'Login to Panel as User',
        'AdminSingleSignOnLabel' => 'Login to Panel as Admin',
    );
}

/**
 * Define product configuration options.
 *
 * The values you return here define the configuration options that are
 * presented to a user when configuring a product for use with the module. These
 * values are then made available in all module function calls with the key name
 * configoptionX - with X being the index number of the field from 1 to 24.
 *
 * You can specify up to 24 parameters, with field types:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each and their possible configuration parameters are provided in
 * this sample function.
 *
 * @return array
 */
function asciossl_ConfigOptions()
{
    return array(
        // a text field type allows for single line text input
        'Username' => array(
            'Type' => 'text',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Ascio account',
        ),
        // a password field type allows for masked text input
        'Password' => array(
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Ascio password',
        ),
        // the yesno field type displays a single checkbox option
        'Testmode' => array(
            'Type' => 'yesno',
            'Description' => ' Connected to the OTE',
        ),
        'CertificateType' => array(
        	'Type' => 'dropdown',
        	'Options' => array(
        		 "quicksslpremium" => "GeoTrust QuickSSL Premium Certificate",
                 "quicksslpremiummd" => "GeoTrust QuickSSL Premium Multi Domain Certificate",
                 "truebizid" => "GeoTrust True BusinessID Certificate",
                 "truebusinessidevmd" => "Geo Trust True Business ID With EV Multi-Domain",
                 "truebusinessidwildcard" => "GeoTrust True BusinessID Wildcard Certificate",
                 "truebusinessidev" => "GeoTrust True BusinessID with EV ",
                 "truebizidmd" => "GeoTrust True BusinessID with Multi-Domain",
                 "malwarescan" => "GeoTrust Web Site Anti-Malware Scan",
                 "thawtecsc" => "Thawte Code Signing Certificate",
                 "thawtecscind" => "Thawte Code Signing Individual Certificate",
                 "sslwebserver" => "thawte SSL Web Server Certificates",
                 "ssl123" => "Thawte SSL123 Certificates",
                 "sslwebserverev" => "Thawte SSLWebserver EV ",
                 "sslwebserverwildcard" => "Thawte Wildcard SSL Certificate",
                 "verisigncsc" => "Symantec Code Signing",
                 "verisigncscind" => "Symantec Code Signing Individual",
                 "securesitewildcard" => "Symantec Secure Site Wildcard",
                 "trustsealorg" => "Symantec Trust Seal",
                 "securesite" => "Symantec Secure Site",
                 "securesitepro" => "Symantec Secure Site Pro",
                 "securesiteproev" => "Symantec Secure Site Pro with EV ",
                 "securesiteev" => "Symantec Secure Site with EV ",
                 "freessl" => "Free SSL Certificate",
                 "rapidssl" => "RapidSSL Certificate",
                 "rapidsslwildcard" => "RapidSSL Wildcard Certificate",
                 "comodocsc" => "Comodo Code Signing Certificate",
                 "comododvucc" => "Domain Validated UCC SSL",
                 "elitessl" => "Comodo Elite SSL",
                 "essentialssl" => "Comodo Essential SSL Certificate",
                 "essentialwildcard" => "Comodo EssentialSSL Wildcard Certificate",
                 "comodoevmdc" => "Comodo EV Multi-Domain SSL Certificate",
                 "comodoevsgc" => "Comodo EV SGC SSL Certificate",
                 "comodoevssl" => "Comodo EV SSL Certificate",
                 "hgpcicontrolscan" => "Comodo HackerGuardian PCI Scan Control Center",
                 "hackerprooftm" => "Comodo HackerProof Trust Mark including Daily Vulnerability Scan",
                 "instantssl" => "Comodo InstantSSL Certificate",
                 "comodopremiumssl" => "Comodo Premium SSL Certificate",
                 "instantsslpro" => "Comodo InstantSSL Pro Certificate",
                 "comodomdc" => "Comodo Multi-Domain SSL Certificate",
                 "comodomdcwildcard" => "Comodo Multi-Domain Wildcard SSL Certificate",
                 "comodopciscan" => "Comodo PCI Scanning Enterprise Edition",
                 "positivemdcssl" => "PositiveSSL Multi-Domain Certificate",
                 "positivemdcwildcard" => "PositiveSSL Multi-Domain Wildcard Certificate",
                 "positivessl" => "Comodo PositiveSSL Certificate",
                 "positivesslwildcard" => "Comodo PositiveSSL Wildcard Certificate",
                 "comodopremiumwildcard" => "Comodo PremiumSSL Wildcard Certificate",
                 "comodosgc" => "Comodo SGC SSL Certificate",
                 "comodosgcwildcard" => "Comodo SGC SSL Wildcard Certificate",
                 "comodossl" => "Comodo SSL Certificate",
                 "comodoucc" => "Comodo Unified Communications Certificate",
                 "comodowildcard" => "Comodo Wildcard SSL Certificate",
                 "comodouccwildcard" => "Comodo Unified Communications Wildcard Certificate",
                 "webinsnterprise" => "Web Inspector Enterprise",
                 "webinsplus" => "Web Inspector Plus",
                 "webinspremium" => "Web Inspector Premium ",
                 "webinsbasic" => "Web Inspector Starter",
                 "ubasicid" => "CERTUM Basic ID Certificate",
                 "ucommercialssl" => "Certum Commercial SSL ",
                 "ucommercialwildcard" => "CERTUM Commercial SSL WildCard Certificate",
                 "uenterpriseid" => "CERTUM Enterprise ID Certificate",
                 "uprofessionalid" => "CERTUM Professional ID Certificate",
                 "utrustedssl" => "CERTUM Trusted SSL Certificate",
                 "utrustedwildcard" => "CERTUM Trusted SSL Wildcard Certificate"
        	)
        )
        // the dropdown field type renders a select menu of options
    );
}
function asciossl_updateOrder($params) {
	try {
		// setup client
		$user = $params["configoption1"];
        $password = $params["configoption2"];
        $testmode = $params["configoption2"]=="on" ? true : false;
        $wsdl = $testmode ? "https://awstest.ascio.com/v3/aws.wsdl" : "https://aws.ascio.com/v3/aws.wsdl";	
    	$header = new SoapHeader('http://www.ascio.com/2013/02','SecurityHeaderDetails', array('Account'=> $user, 'Password'=>$password), false);
        $ascioClient     = new ascio\AscioService(array("trace" => true, "encoding" => "ISO-8859-1"),$wsdl);
		$ascioClient->__setSoapHeaders($header);
    	// get database data
    	$result = mysql_query("select id,remoteid,status from tblsslorders where serviceid='".$params["serviceid"]."'");
    	$sslOrderData = mysql_fetch_assoc($result);   	
    	$result       = select_query("mod_asciossl","order_id,certificate_id,token",array("id"=>$sslOrderData["id"]));
    	$ascioResult = mysql_fetch_array($result);
    	$orderId = $ascioResult["order_id"];
    	$token   = $ascioResult["token"];
    	$certificateId   = $ascioResult["certificate_id"];
    	$completed = array("Completed","Failed","Invalid");     	
    	$orderStatus = $sslOrderData["status"];
    	if(array_search($completed,$orderStatus)) {
			$certificateId = $ascioResult["certificate_id"];
    	} else {
			// get order - write tblsslorders
			$orderRequest = new ascio\GetOrderRequest();
			$orderRequest->setOrderId($orderId);
			$response = $ascioClient->GetOrder(new ascio\GetOrder($orderRequest));
			$certificateId = $response->GetOrderResult->GetOrderInfo()->getOrderRequest()->getSslCertificate()->getHandle();
			$orderStatus = $response->GetOrderResult->GetOrderInfo()->GetStatus();				
			update_query("tblsslorders",array("certificate_id" => $certificateId, "status" => $orderStatus),array("id" => $sslOrderData["id"] ));
			// get certificate - write mod_asciossl
			$getSslCertificateRequest = new ascio\GetAutoInstallSslRequest();
			$getSslCertificateRequest->setHandle($certificateId);
			$response = $ascioClient->GetAutoInstallSsl(new ascio\GetAutoInstallSsl($getSslCertificateRequest));
			$result = $response->GetAutoInstallSslResult->GetAutoInstallSslInfo();
			$token = $result->getToken();
			$status = $result->getStatus();				
			update_query("mod_asciossl",array("status" => $status,"token" => $token), array("id" => $sslOrderData["id"] ));
		}


		
        // Call the service's function, using the values provided by WHMCS in
        // `$params`.
        $response = array();
        $tokens = explode("#",$token);
        $tokenCode = $tokens[0];
        $tokenId = $tokens[1];

        // Return an array based on the function's response.
        return array(
            'AutoInstallSsl Token Code' => $tokenCode,
            'AutoInstallSsl Token ID' => $tokenId,
            'Ascio Order ID' => $orderId,
            'Ascio Certificate ID' => $certificateId,
            'Status' => $orderStatus
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        // In an error condition, simply return no additional fields to display.
    }

    return array();
}
/**
 * Provision a new instance of a product/service.
 *
 * Attempt to provision a new instance of a given product/service. This is
 * called any time provisioning is requested inside of WHMCS. Depending upon the
 * configuration, this can be any of:
 * * When a new order is placed
 * * When an invoice for a new order is paid
 * * Upon manual request by an admin user
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function asciossl_CreateAccount(array $params)
{
    try {
        $user = $params["configoption1"];
		$password = $params["configoption2"];
        $testmode = $params["configoption3"]=="on" ? true : false;
        $certtype = $params["configoption4"];
        $certyears = $params["configoptions"]["Years"];
        $wsdl = $testmode ? "https://awstest.ascio.com/v3/aws.wsdl" : "https://aws.ascio.com/v3/aws.wsdl";  
		$header = new SoapHeader('http://www.ascio.com/2013/02','SecurityHeaderDetails', array('Account'=> $user, 'Password'=>$password), false);
        $ascioClient     = new ascio\AscioService(array("trace" => true, "encoding" => "ISO-8859-1"),$wsdl);
		$ascioClient->__setSoapHeaders($header);
		$orderRequest = new ascio\AutoInstallSslOrderRequest(ascio\OrderType::Register);
		$orderRequest->setPeriod($certyears); 
		$autoInstallSsl = new ascio\AutoInstallSsl(0);
		$autoInstallSsl->setCommonName(uniqid("SSL-Domain-"));
		$autoInstallSsl->setProductCode($certtype);
		//$autoInstallSsl->setEmail($params["customfields"]["Approval Email"]);
		$orderRequest->setAutoInstallSsl($autoInstallSsl);
		$createOrder = new ascio\CreateOrder($orderRequest);
		$response = $ascioClient->createOrder($createOrder); 
		$orderInfo = $response->CreateOrderResult->getOrderInfo();
        if($response->CreateOrderResult->getResultCode() != 200) {
            return join(", ",$response->CreateOrderResult->getErrors()->getString());
        }
		$orderId = $orderInfo->getOrderId();
         // 1. Create record at WHMCS tblssorders table
        $queryData = array(
            "userid" => $params["clientsdetails"]["userid"],
            "serviceid" => $params["serviceid"],
            "remoteid" => $orderId,
            "module" => "asciossl",
            "certtype" => $certtype,
            "status" => $orderInfo->getStatus()
        );
 	   $sslorderid =insert_query('tblsslorders',$queryData);
    	// 2. Create record at custom module table
	    $queryData = array(
    	    'id' => $sslorderid,
        	'user_id' => $params["clientsdetails"]["userid"],
        	'order_id' => $orderId,
        	'type' => $certtype,
        	'period' => $certyears,
    	);
    	insert_query('mod_asciossl', $queryData);
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );      
        return $e->getMessage();
    }

    return 'success';
}
/**
 * Admin services tab additional fields.
 *
 * Define additional rows and fields to be displayed in the admin area service
 * information and management page within the clients profile.
 *
 * Supports an unlimited number of additional field labels and content of any
 * type to output.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 * @see asciossl_AdminServicesTabFieldsSave()
 *
 * @return array
 */
function asciossl_AdminServicesTabFields(array $params)
{
    return asciossl_updateOrder($params);
}
/**
 * Suspend an instance of a product/service.
 *
 * Called when a suspension is requested. This is invoked automatically by WHMCS
 * when a product becomes overdue on payment or can be called manually by admin
 * user.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function asciossl_SuspendAccount(array $params)
{
    try {
        // Call the service's suspend function, using the values provided by
        // WHMCS in `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Un-suspend instance of a product/service.
 *
 * Called when an un-suspension is requested. This is invoked
 * automatically upon payment of an overdue invoice for a product, or
 * can be called manually by admin user.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function asciossl_UnsuspendAccount(array $params)
{
    try {
        // Call the service's unsuspend function, using the values provided by
        // WHMCS in `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Terminate instance of a product/service.
 *
 * Called when a termination is requested. This can be invoked automatically for
 * overdue products if enabled, or requested manually by an admin user.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function asciossl_TerminateAccount(array $params)
{
    try {
        // Call the service's terminate function, using the values provided by
        // WHMCS in `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Change the password for an instance of a product/service.
 *
 * Called when a password change is requested. This can occur either due to a
 * client requesting it via the client area or an admin requesting it from the
 * admin side.
 *
 * This option is only available to client end users when the product is in an
 * active status.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function asciossl_ChangePassword(array $params)
{
    try {
        // Call the service's change password function, using the values
        // provided by WHMCS in `$params`.
        //
        // A sample `$params` array may be defined as:
        //
        // ```
        // array(
        //     'username' => 'The service username',
        //     'password' => 'The new service password',
        // )
        // ```
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Upgrade or downgrade an instance of a product/service.
 *
 * Called to apply any change in product assignment or parameters. It
 * is called to provision upgrade or downgrade orders, as well as being
 * able to be invoked manually by an admin user.
 *
 * This same function is called for upgrades and downgrades of both
 * products and configurable options.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return string "success" or an error message
 */
function asciossl_ChangePackage(array $params)
{
    try {
        // Call the service's change password function, using the values
        // provided by WHMCS in `$params`.
        //
        // A sample `$params` array may be defined as:
        //
        // ```
        // array(
        //     'username' => 'The service username',
        //     'configoption1' => 'The new service disk space',
        //     'configoption3' => 'Whether or not to enable FTP',
        // )
        // ```
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Test connection with the given server parameters.
 *
 * Allows an admin user to verify that an API connection can be
 * successfully made with the given configuration parameters for a
 * server.
 *
 * When defined in a module, a Test Connection button will appear
 * alongside the Server Type dropdown when adding or editing an
 * existing server.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return array
 */
function asciossl_TestConnection(array $params)
{
    try {
        // Call the service's connection test function.

        $success = true;
        $errorMsg = '';
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        $success = false;
        $errorMsg = $e->getMessage();
    }

    return array(
        'success' => $success,
        'error' => $errorMsg,
    );
}

/**
 * Additional actions an admin user can invoke.
 *
 * Define additional actions that an admin user can perform for an
 * instance of a product/service.
 *
 * @see asciossl_buttonOneFunction()
 *
 * @return array
 */
function asciossl_AdminCustomButtonArray()
{
    return array(
        "Renew certificate" => "renew"
    );
}

/**
 * Additional actions a client user can invoke.
 *
 * Define additional actions a client user can perform for an instance of a
 * product/service.
 *
 * Any actions you define here will be automatically displayed in the available
 * list of actions within the client area.
 *
 * @return array
 */
function asciossl_ClientAreaCustomButtonArray()
{
    return array(
        "Renew certificate" => "renew"
    );
}

/**
 * Custom function for performing an additional action.
 *
 * You can define an unlimited number of custom functions in this way.
 *
 * Similar to all other module call functions, they should either return
 * 'success' or an error message to be displayed.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 * @see asciossl_AdminCustomButtonArray()
 *
 * @return string "success" or an error message
 */
function asciossl_buttonOneFunction(array $params)
{
    try {
        // Call the service's function, using the values provided by WHMCS in
        // `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Custom function for performing an additional action.
 *
 * You can define an unlimited number of custom functions in this way.
 *
 * Similar to all other module call functions, they should either return
 * 'success' or an error message to be displayed.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 * @see asciossl_ClientAreaCustomButtonArray()
 *
 * @return string "success" or an error message
 */
function asciossl_actionOneFunction(array $params)
{
    try {
        // Call the service's function, using the values provided by WHMCS in
        // `$params`.
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}



/**
 * Execute actions upon save of an instance of a product/service.
 *
 * Use to perform any required actions upon the submission of the admin area
 * product management form.
 *
 * It can also be used in conjunction with the AdminServicesTabFields function
 * to handle values submitted in any custom fields which is demonstrated here.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 * @see asciossl_AdminServicesTabFields()
 */
function asciossl_AdminServicesTabFieldsSave(array $params)
{
    // Fetch form submission variables.
    $originalFieldValue = isset($_REQUEST['asciossl_original_uniquefieldname'])
        ? $_REQUEST['asciossl_original_uniquefieldname']
        : '';

    $newFieldValue = isset($_REQUEST['asciossl_uniquefieldname'])
        ? $_REQUEST['asciossl_uniquefieldname']
        : '';

    // Look for a change in value to avoid making unnecessary service calls.
    if ($originalFieldValue != $newFieldValue) {
        try {
            // Call the service's function, using the values provided by WHMCS
            // in `$params`.
        } catch (Exception $e) {
            // Record the error in WHMCS's module log.
            logModuleCall(
                'asciossl',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );

            // Otherwise, error conditions are not supported in this operation.
        }
    }
}

/**
 * Perform single sign-on for a given instance of a product/service.
 *
 * Called when single sign-on is requested for an instance of a product/service.
 *
 * When successful, returns a URL to which the user should be redirected.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return array
 */
function asciossl_ServiceSingleSignOn(array $params)
{
    try {
        // Call the service's single sign-on token retrieval function, using the
        // values provided by WHMCS in `$params`.
        $response = array();

        return array(
            'success' => true,
            'redirectTo' => $response['redirectUrl'],
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return array(
            'success' => false,
            'errorMsg' => $e->getMessage(),
        );
    }
}

/**
 * Perform single sign-on for a server.
 *
 * Called when single sign-on is requested for a server assigned to the module.
 *
 * This differs from ServiceSingleSignOn in that it relates to a server
 * instance within the admin area, as opposed to a single client instance of a
 * product/service.
 *
 * When successful, returns a URL to which the user should be redirected to.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return array
 */
function asciossl_AdminSingleSignOn(array $params)
{
    try {
        // Call the service's single sign-on admin token retrieval function,
        // using the values provided by WHMCS in `$params`.
        $response = array();

        return array(
            'success' => true,
            'redirectTo' => $response['redirectUrl'],
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'asciossl',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return array(
            'success' => false,
            'errorMsg' => $e->getMessage(),
        );
    }
}

/**
 * Client area output logic handling.
 *
 * This function is used to define module specific client area output. It should
 * return an array consisting of a template file and optional additional
 * template variables to make available to that template.
 *
 * The template file you return can be one of two types:
 *
 * * tabOverviewModuleOutputTemplate - The output of the template provided here
 *   will be displayed as part of the default product/service client area
 *   product overview page.
 *
 * * tabOverviewReplacementTemplate - Alternatively using this option allows you
 *   to entirely take control of the product/service overview page within the
 *   client area.
 *
 * Whichever option you choose, extra template variables are defined in the same
 * way. This demonstrates the use of the full replacement.
 *
 * Please Note: Using tabOverviewReplacementTemplate means you should display
 * the standard information such as pricing and billing details in your custom
 * template or they will not be visible to the end user.
 *
 * @param array $params common module parameters
 *
 * @see http://docs.whmcs.com/Provisioning_Module_SDK_Parameters
 *
 * @return array
 */
function asciossl_ClientArea(array $params)
{
    // Determine the requested action and set service call parameters based on
    // the action.
    $requestedAction = isset($_REQUEST['customAction']) ? $_REQUEST['customAction'] : '';

    if ($requestedAction == 'manage') {
        $serviceAction = 'get_usage';
        $templateFile = 'templates/manage.tpl';
    } else {
        $serviceAction = 'get_stats';
        $templateFile = 'templates/overview.tpl';
    }
    $data = asciossl_updateOrder($params);
    return array(
            'AutoInstallSsl Token Code' => $data['AutoInstallSsl Token Code'],
            'AutoInstallSsl Token ID' => $data['AutoInstallSsl Token Code'],
            'Status' => $data['Status']
    );
       
}
