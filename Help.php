<?php
error_reporting(0);
include('Warehouse.php');

if (file_exists('Help_'.mb_substr($locale, 0, 2).'.php')) //modif Francois: translated help
	include 'Help_'.mb_substr($locale, 0, 2).'.php';
else
	include 'Help_en.php';

switch(User('PROFILE'))
{
	case 'admin':
		$title = _('Administrator');
	break;

	case 'teacher':
		$title = _('Teacher');
	break;

	case 'parent':
		$title = _('Parent');
	break;
	
	case 'student':
		$title = _('Student');
	break;
}

$handle = PDFStart();
?>
<TABLE>
	<TR>
		<TD><IMG SRC="assets/themes/<?php echo Preferences('THEME'); ?>/logo.png" /></TD>
		<TD><h1><?php echo $_REQUEST['modname'] = sprintf(_('%s Handbook'),$title); ?></h1></TD>
	</TR>
</TABLE>
<HR>
<?php
foreach($help as $program=>$value)
{
	$_REQUEST['modname'] = $program;
	if(mb_strpos($program,'/'))
	{
		$modcat = str_replace('_',' ',mb_substr($program,0,mb_strpos($program,'/')));
		
		if (!$RosarioModules[str_replace(' ','_',$modcat)]) //module not activated
			break;
	
		if($modcat!=$old_modcat && $modcat!='Custom') : ?>

			<div style="page-break-after: always;"></div>

<?php
			unset($_ROSARIO['DrawHeader']);
			$_ROSARIO['HeaderIcon'] = 'modules/'.str_replace(' ','_',$modcat).'/icon.png';
			echo DrawHeader(_($modcat));
?>
			<HR>

		<?php endif;
		if ($modcat!='Custom')
			$old_modcat = $modcat;
	}
	 ?>

	<div style="page-break-inside: avoid;"><h3>

	<?php if($program=='default')
		echo ParseMLField(Config('TITLE')).' - '.sprintf(_('%s Handbook'),$title).'<BR />'.sprintf(_('version %s'),'1.1');
	else
		echo (ProgramTitle() == 'RosarioSIS' ? $program : ProgramTitle());
	?>

	</h3>
	<TABLE class="width-100p"><TR><TD class="header2">

	<?php if(User('PROFILE') == 'student')
		$value = str_replace('your child','yourself',str_replace('your child\'s','your',$value));
	$value = str_replace('RosarioSIS', Config('NAME'),$value);
	echo $value; ?>

	</TD></TR></TABLE>
	</div>
	<BR />

<?php
}
?>
<div style="text-align:center;font-weight:bold;"><a href="http://www.rosariosis.org/">http://www.rosariosis.org/</a></div>
<?php
$_REQUEST['modname'] = '';
PDFStop($handle);
?>
