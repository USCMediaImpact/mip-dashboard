<?php

namespace App\Helpers;

class FormatterHelper
{
	public static function percent($numerator, $denominator)
	{
		if($denominator){
			return sprintf("%.2f%%", $numerator / $denominator * 100);
		}
		return '0%';

	}

	public static function date($value){
		return date('m/d/y', strtotime($value));
	}
}