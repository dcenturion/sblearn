<?php
require_once('./sviews/layout.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/sbookstores/php/functions.php');
class UserAdministrator{

	private $_parm;
    public  function __construct($_parm=null)
	{
	
		$BodyPage = "hahaha";	
		echo $layout->main($BodyPage,$datos);

	}

		

	

}