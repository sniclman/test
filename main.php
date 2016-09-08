<?
//session_start();
//$_SESSION['detectedDebugerError'] = false;
//require_once 'tracy/tracy/tracy.php';
//use Tracy\Debugger;
//Debugger::enable(Debugger::DEVELOPMENT);
//Debugger::$strictMode = TRUE;
//Debugger::barDump($_SESSION);
require_once 'bootloader.php';

xxxx

moje další změna

tak to jo kámo

já jsem KLON

if(isset($_GET['robots'])){require_once 'robots.php'; exit;}
if(isset($_GET['sitemap'])){require_once 'sitemap.php'; exit;}
if(isset($_GET['phpinfo'])){require_once 'phpinfo.php'; exit;}



/*DEFINE CLASS FUNCTIONS*/
$debugConsole = new debugConsole();
$fn = new functions();
$images = new images(__WWW_ROOT__, __SITE_ROOT__, 'cache');
$asyncScripts = new asyncScripts(__WWW_ROOT__, __SITE_ROOT__);
$googleAnalitics = new googleAnalitics(__WWW_ROOT__, 'cache', 'UA-13141249-54');
$socialSites = new socialSites();
$lang = new language('cs', 'en');
$minify = new minify(__WWW_ROOT__, 'cache');



/*SET LANGUAGE*/
$lang->setFileLangPath(__APP_ROOT__.'lang/');
$lang->setLangImage('cs', $images->resolution('frontend/graphic/cs.png', 27, 27, 'jpeg', 100));
$lang->setLangImage('en', $images->resolution('frontend/graphic/en.png', 27, 27, 'jpeg', 100));
$lang->setLanguage();



/*SET PAGES LOADERS*/
$pageLoader = new pageLoader(__APP_ROOT__, 'pages', 'cache', array('lang' => $lang));
$fn->{'files'} = $pageLoader->{'files'}; 



/*GET PAGES FUNCTIONS*/
if($pageLoader->getFunctions($fn->is_get(0))){
foreach($pageLoader->getFunctions($fn->is_get(0)) as $include){require_once $include;}
}



/*SET HTML HEAD*/
$fn->setCharSet('utf-8');
$fn->setAuthor('matraux.com; matraux@email.cz');
$fn->setKeyWords('cs', 'ambilight, okolní, podsvícení, systém');
$fn->setKeyWords('en', 'ambilight, ambient, light, system');
$fn->setDescription('cs', 'Ambilight MATRAUX - systém okolního podsvícení LCD nebo TV panelů');
$fn->setDescription('en', 'Ambilight MATRAUX - ambient lighting system for LCD or TV panel');
$fn->setMetaData('viewport', 'width=device-width, initial-scale=1');
$fn->setMetaData('robots', 'all');
$fn->setDefaultTitle($lang->set('default.title'));
$fn->setHeadTitle('Ambilight | '.$fn->getTitle($fn->is_get(0)));



//setup minify charset
$minify->setCharset($fn->getCharSet());



/*COMPILE SCRIPTS*/
//minify google analytics script
$ga = $minify->setContentType('js');
$ga = $googleAnalitics->getScript();
$ga = $minify->compressFile($ga);

//compile and minify all javascripts in folder
$javascript = $minify->setContentType('js');
$javascript = $minify->compile('/frontend/js', 'js');
$javascript = $minify->compressFile($javascript);

//compile and minify all css in folder
$css = $minify->setContentType('css');
$css = $minify->compile('/frontend/css', 'css');
$css = $minify->compressFile($css);


/*SET ASYNCSCRIPTS*/
//minify asyncloader script
$asyncLoader = $minify->setContentType('js');
$asyncLoader = $minify->compressFile(__APP_ROOT__.'asyncLoader.js');
$asyncScripts->setAsyncLoader($asyncLoader);

//setup async scripts
$asyncScripts->setAsyncScript($ga, 'js');
$asyncScripts->setAsyncScript($css, 'css');
$asyncScripts->setAsyncScript($javascript, 'js');



/*SET SOCIALS SITES*/
$socialSites->addIcon('http://ambilight.matraux.com', 'fa fa-facebook facebook');
$socialSites->addIcon('http://ambilight.matraux.com', 'fa fa-youtube youtube');
$socialSites->addIcon('http://ambilight.matraux.com', 'fa fa-rss rss');




//minifykuje hlavní obsah a nastaví hlavičky
$minify->setContentType('html');
$minify->streamStart();

?>
<!DOCTYPE html>
<html>
<head>
<?$fn->constructHead();?>
</head>
<body>


<h1><?=$lang->set('h1');?></h1>
<h2><?=$lang->set('h2');?></h2>


<!-- MAIN MENU-->
<?require_once __APP_ROOT__.'pages/mainComponents/main_menu.php';?>
<!-- MAIN MENU-->


<!-- SOCIAL SITES -->
<?$socialSites->constructIcons();?>
<!-- SOCIAL SITES -->


<!-- CONTENT -->
<div class="max_size <?=(empty($_POST['DIRECTION'])?null:$_POST['DIRECTION'].'_center');?> scrolBarInit">
<div class="inner_size">
<?
if($pageLoader->getPage($fn->is_get(0))){
foreach($pageLoader->getPage($fn->is_get(0)) as $include){require_once $include;}}
?>
<div class="inner_padding"></div>
</div>
</div>
<!-- CONTENT -->


<!-- FOOTER -->
<?require_once __APP_ROOT__.'pages/mainComponents/footer.php';?>
<!-- FOOTER -->


<!-- ALERT -->
<?$fn->releaseAlert();?>
<!-- ALERT -->


<!-- ASYNC SCRIPTS -->
<?$asyncScripts->constructAsyncScripts();?>
<!-- ASYNC SCRIPTS -->



</body>
</html>


