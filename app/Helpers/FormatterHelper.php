<?php

namespace App\Helpers;

class FormatterHelper
{
	public static function showAsPercent($value){
	    if(is_numeric($value)){
            return sprintf("%.2f%%", round($value, 2));
        }
		return 'N/A';
	}

	public static function percentWithSymbol($value){
        if(is_numeric($value)){
            $fix_value = round($value, 2);
            if($fix_value > 0){
                return '+'. sprintf("%.2f%%", $fix_value);
            }else{
                return sprintf("%.2f%%", $fix_value);
            }
        }
        return 'N/A';
    }

	public static function percent($numerator, $denominator)
	{
		if($denominator){
			return sprintf("%.2f%%", round($numerator / $denominator * 100, 2));
		}
		return '0%';
	}

	public static function date($value){
		return date('m/d/y', strtotime($value));
	}
}