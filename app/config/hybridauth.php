<?php
return array(	
	"base_url"   => url() . '/social/auth',
	"providers"  => array (
		"Google"     => array (
			"enabled"    => true,
			"keys"       => array ( "id" => "AIzaSyBhhuSpwpJqXK63OF-8NwcpU48FMpuyp5o", "secret" => "NxeUpO8U7JrLnFKXoFuVPw2k" ),
			"scope"      => "https://www.googleapis.com/auth/userinfo.profile ".
                            "https://www.googleapis.com/auth/userinfo.email"   ,
			),
		"Facebook"   => array (
			"enabled"    => true,
			"keys"       => array ( "id" => "728317177187176", "secret" => "90aaf543e990e1283c0c2e922c068c37" ),
			'scope'      =>  'email',
			),
		"Twitter"    => array (
			"enabled"    => true,
			"keys"       => array ( "key" => "m6aeaD1bdFrpdbHMgDY4CZ7ut", "secret" => "DwP1C94upMK2VVYH2CdXcxRWxgIPnTKwa3fW6A057DponZvXR7" )
			)
	),
);