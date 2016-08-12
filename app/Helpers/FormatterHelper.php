<?php

namespace App\Helpers;

class FormatterHelper
{
	public static function showAsPercent($value){
	    if(is_numeric($value)){
            return sprintf("%.2f%%", $value);
        }
		return 'N/A';
	}

	public static function percentWithSymbol($value){
        if(is_numeric($value)){
            if($value > 0){
                return '+'. sprintf("%.2f%%", $value);
            }else{
                return sprintf("%.2f%%", $value);
            }
        }
        return 'N/A';
    }

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