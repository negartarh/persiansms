<?php


use Negartarh\Persiansms\PersianSms;

require_once __DIR__ . '/../vendor/autoload.php';

$user    = (string) '';
$pass    = (string) '';
$from    = (string) '';
$text    = (string) '';
$numbers = (array) [''];

$sms = ( new PersianSms() )
	->withUser( $user )
	->withPass( $pass )
	->from( $from )
	->send( $text, $numbers );