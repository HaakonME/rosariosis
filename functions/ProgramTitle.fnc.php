<?php

function ProgramTitle($modname='')
{	global $_ROSARIO;

	if(!$modname)
		$modname = $_REQUEST['modname'];
	if(!isset($_ROSARIO['Menu']))
	{
		global $RosarioModules;
		include 'Menu.php';
	}
	foreach($_ROSARIO['Menu'] as $modcat=>$programs)
	{
		if(count($programs))
		{
			foreach($programs as $program=>$title)
			{
				if($modname==$program)
				{
					if($_ROSARIO['HeaderIcon']!==false)
						if(mb_substr($modname,0,25)=='Users/TeacherPrograms.php')
							$_ROSARIO['HeaderIcon'] = 'modules/'.mb_substr($modname,34,mb_strpos($modname,'/',34)-34).'/icon.png';
						else
							$_ROSARIO['HeaderIcon'] = 'modules/'.$modcat.'/icon.png';
					return $title;
				}
			}
		}
	}
	if($_ROSARIO['HeaderIcon']!==false)
		unset($_ROSARIO['HeaderIcon']);
	return 'RosarioSIS';
}
?>
