<?php
session_start();
$folder_level = "./";
while (!file_exists($folder_level."base.php")) {$folder_level .= "../";}
define("BASEDIR", $folder_level);
include(BASEDIR . "vendor/autoload.php");

include(BASEDIR . "controller/Request.php");
include(BASEDIR . "controller/IPP.php");
include(BASEDIR . "controller/IPPPayments.php");
include(BASEDIR . "controller/IPPCurrency.php");
include(BASEDIR . "controller/IPPPartner.php");
include(BASEDIR . "controller/IPPPlugins.php");
include(BASEDIR . "controller/IPPMenu.php");
include(BASEDIR . "controller/IPPUtils.php");

if (file_exists(BASEDIR . "ipp-config.php")) {
    include BASEDIR . "ipp-config.php";
} else {
    header("Location: ".BASEDIR."setup");
    die();
}
$_ENV           = $IPP_CONFIG;
$id             = isset($_COOKIE["ipp_user_id"]) ? $_COOKIE["ipp_user_id"] : "";
$session_id     = isset($_COOKIE["ipp_user_session_id"]) ? $_COOKIE["ipp_user_session_id"] : "";
$login_type     = isset($_COOKIE["ipp_type"]) ? $_COOKIE["ipp_type"] : "";

define("THEMES", BASEDIR . "theme/");
define("THEME", THEMES . $_ENV["THEME"]);


$request    = new IPPRequest();
$ipp        = new IPP($request,$id,$session_id);
$partner    = new IPPPartner($request,$id,$session_id);

$plugins    = new IPPPlugins($request);
$currency   = new IPPCurrency();
$RequestP   = new RequestParams($request);
$mcc        = new MCC();
$menu       = new IPPMenu();
$utils      = new IPPUtils();
if(isset($_COOKIE["timezone"])) {
    $timezoneoffset = $utils->getTimezoneBasedOnOffsetMinutes($_COOKIE["timezone"]);
    if($timezoneoffset <> "") {
        date_default_timezone_set($timezoneoffset);
    }
}

$langs = $utils->prefered_language(["da","en","da-dk","en-gb"], $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
$language   = $_COOKIE['language'] ?? array_key_first($langs);
$REQ        = $RequestP->getRequestParams($_SERVER["REQUEST_METHOD"]);

$inline_css = [];
$inline_script = [];
$load_script = [];
$load_css = [];
$company_data = new stdClass();
$yes_no_select   = ["No","Yes"];

if(isset($partner_page) && $partner_page == 1) {
    $data = $partner->checkLogin();
    if(!$data->success) {
        header("Location: /");
        die();
    }
    $user_data = $partner->UserData();
    require_once(THEME."/functions.php");
    require_once(THEME."/partner/head.php");
    require_once(THEME."/partner/foot.php");
}
elseif(!isset($public_page) || (isset($public_page) && !$public_page)) {
    $company_data = $ipp->checkLogin();
    if(!$company_data->success) {
        header("Location: /");
        die();
    }
    require_once("theme/".$_ENV["THEME"]."/functions.php");
    require_once("theme/".$_ENV["THEME"]."/head.php");
    require_once("theme/".$_ENV["THEME"]."/foot.php");
}
$plugins->loadPlugins();

require_once(BASEDIR . "language/".$language.".php");
if(file_exists(THEME . "/language/$language.php"))
require_once(THEME . "/language/$language.php");

