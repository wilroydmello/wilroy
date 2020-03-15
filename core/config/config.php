<?php
namespace wilroy\core\config;
class config
{
	function getConfigVars()
	{
		$ini_array = parse_ini_file(__DIR__ . "/config.ini", true);
		unset($ini_array['db']);
		return $ini_array;
	}
}

?>
