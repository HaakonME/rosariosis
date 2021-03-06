<?php
error_reporting(1);
include('Warehouse.php');

if($_REQUEST['modfunc']=='print')
{
//modif Francois: call PDFStart to generate Print PDF
	if($_REQUEST['expanded_view'])
		$_SESSION['orientation'] = 'landscape';
		
	$print_data = PDFStart();
	
	$_REQUEST = $_SESSION['_REQUEST_vars'];
	$_REQUEST['_ROSARIO_PDF'] = true;
	$modname = $_REQUEST['modname'];
	
	if(!$wkhtmltopdfPath)
		$_ROSARIO['allow_edit'] = false;
		
	//modif Francois: security fix, cf http://www.securiteam.com/securitynews/6S02U1P6BI.html
	if (mb_substr($modname, -4, 4)!='.php' || mb_strpos($modname, '..')!==false || !is_file('modules/'.$modname))
	{
		include('ProgramFunctions/HackingLog.fnc.php');
		HackingLog();
	}
	else
		include('modules/'.$modname);
		
//modif Francois: call PDFStop to generate Print PDF
	PDFStop($print_data);
}
elseif($_REQUEST['modfunc']=='help')
{
	if (file_exists('Help_'.mb_substr($locale, 0, 2).'.php')) //modif Francois: translated help
		include 'Help_'.mb_substr($locale, 0, 2).'.php';
	else
		include 'Help_en.php';

	$help_text = '';

	foreach($help as $program=>$help_txt)
	{
		//modif Francois: fix bug URL Modules.php?modfunc=help&modname=Student_Billing/Statements.php&_ROSARIO_PDF
		if($_REQUEST['modname']==$program || (mb_strpos($program, $_REQUEST['modname'])=== 0 && mb_strpos($_SERVER['QUERY_STRING'], $program)=== 21))
			$help_text = $help_txt;
	}

	if(empty($help_text))
		$help_text = $help['default'];
		
	if(User('PROFILE') == 'student')
		$help_text = str_replace('your child','yourself',str_replace('your child\'s','your',$help_text));

	$help_text = str_replace('RosarioSIS', Config('NAME'),$help_text);
	
	echo $help_text;
}
else
{ ?>

		<div id="footerwrap">
			<a id="BottomButtonMenu" href="#" onclick="expandMenu(); return false;" title="<?php echo _('Menu'); ?>" class="BottomButton">&nbsp;<span><?php echo _('Menu'); ?></span></a>

			<?php //modif Francois: icons
			if($_SESSION['List_PHP_SELF'] && (User('PROFILE')=='admin' || User('PROFILE')=='teacher')) :
				switch ($_SESSION['Back_PHP_SELF']) {
					case 'student': $back_text = _('Student List'); break;
					case 'staff': $back_text = _('User List'); break;
					case 'course': $back_text = _('Course List'); break;
					default: $back_text = sprintf(_('%s List'),$_SESSION['Back_PHP_SELF']);
				} ?>
				
				<a href="<?php echo $_SESSION['List_PHP_SELF']; ?>&bottom_back=true" title="<?php echo $back_text; ?>" class="BottomButton"><img src="assets/themes/<?php echo Preferences('THEME'); ?>/btn/back.png" />&nbsp;<span><?php echo $back_text; ?></span></a>

			<?php endif;
			
			if($_SESSION['Search_PHP_SELF'] && (User('PROFILE')=='admin' || User('PROFILE')=='teacher')) :
				switch ($_SESSION['Back_PHP_SELF']) {
					case 'student': $back_text = _('Student Search'); break;
					case 'staff': $back_text = _('User Search'); break;
					case 'course': $back_text = _('Course Search'); break;
					default: $back_text = sprintf(_('%s Search'),$_SESSION['Back_PHP_SELF']);
				} ?>

				<a href="<?php echo $_SESSION['Search_PHP_SELF']; ?>&bottom_back=true" title="<?php echo $back_text; ?>" class="BottomButton"><img src="assets/themes/<?php echo Preferences('THEME'); ?>/btn/back.png" />&nbsp;<span><?php echo $back_text; ?></span></a>

			<?php endif; ?>

			<a href="Bottom.php?modfunc=print" target="_blank" title="<?php echo _('Print'); ?>" class="BottomButton"><img src="assets/themes/<?php echo Preferences('THEME'); ?>/btn/print.png" />&nbsp;<span><?php echo _('Print'); ?></span></a>
			<a href="#" onclick="toggleHelp();return false;" title="<?php echo _('Help'); ?>" class="BottomButton"><img src="assets/themes/<?php echo Preferences('THEME'); ?>/btn/help.png" />&nbsp;<span><?php echo _('Help'); ?></span></a>
			<a href="index.php?modfunc=logout" target="_top" title="<?php echo _('Logout'); ?>" class="BottomButton"><img src="assets/themes/<?php echo Preferences('THEME'); ?>/btn/logout.png" />&nbsp;<span><?php echo _('Logout'); ?></span></a>
			<img id="BottomSpinner" class="BottomButton" src="assets/themes/<?php echo Preferences('THEME'); ?>/spinning.gif" alt="Loading" />
		</div>

		<div id="footerhelp"></div>

<?php
}
?>
