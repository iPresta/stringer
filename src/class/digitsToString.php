<?php

class digitsToString
{
    public $class;
    public $digits;
    public $lang = 'en';

    public function __construct($lang = 'en')
    {
        $this->lang = $lang;
        include_once 'lang/'.$this->lang.'.php';
        $this->class = 'words_'.$this->lang;
    }

    public function convert($digits, $separator = ',', $point = '.')
    {
        $words = '';
        if (!is_int($digits)) {
            $array = explode($point, $digits);
			if (count($array) > 2) {
				return $words;
			}
            $array[0] = str_replace(' ', '', $array[0]);
            $array[0] = str_replace($separator, '', $array[0]);
            $digits = (int)$array[0];
            $decimal = @(int)$array[1];
            $words = $this->convertDigits($digits, $decimal);
        }
        return $words;
    }

    public function convertDigits($digits, $decimal)
    {
        $class = $this->class;
        if ($digits == 0) {
            return $class::$zero;
        }
        $array = array_reverse(str_split(strrev($digits), 3));
        $length = count($array);
        $output = '';
        foreach ($array as $key => $value) {
            $unit = $length - ($key + 1);
            $before = (!$key || !(int)$value ? false : true);
            $output .= $this->convertLayer($value, $unit, $before);
        }
        if ($decimal) {
            $output .= ' ' . $class::$point . ' ';
            $array = array_reverse(str_split(strrev($decimal), 3));
            $length = count($array);
            foreach ($array as $key => $value) {
                $unit = $length - ($key + 1);
                $before = (!$key || !(int)$value ? false : true);
                $output .= $this->convertLayer($value, $unit, $before);
            }
        }
        return $output;
    }

    public function convertLayer($digits, $unit, $before)
    {
        $class = $this->class;
        $output = $before ? ' ' . $class::$unitSeparator . ' ' : '';
        $split = str_split($digits);
        $digits = strrev($digits);
        $handleUnit = (int)$digits;
        if ($digits > 99) {
            $output .= $class::$layer_4[$split[2] - 1].($split[1] || $split[0] ? ' ' . $class::$separator . ' ' : ' ');
            $digits = $digits - (100*($split[2]));
        }
        if ($digits > 19) {
            $output .= $class::$layer_3[$split[1] - 2].($split[0] ? ' ' . $class::$separator . ' ' : ' ');
            $digits = $digits - ($split[1] * 10);
        }
        if ($digits > 10) {
            $output .= $class::$layer_2[$split[0] - 1] . ' ';
            $digits = 0;
        }
        if ((int)$digits) {
            $output .= $class::$layer_1[$split[0]] . ' ';
        }
        if ($unit && $handleUnit) {
            $output .= $class::$units[$unit - 1];
        }
        return $output;
    }

    /**
     * @param $date
     * @param $outputType : full, month, justMonth
     * @return string
     */
    public function dateConvert($date, $outputType = 'full')
    {
        $class = $this->class;
        $dateArray = false;
        $delimiters = array(' ', '-', '_', '.', '/', '\\');
        foreach ($delimiters as $delimiter) {
            if (!$dateArray && strpos($date, $delimiter)) {
                $dateArray = explode($delimiter, $date);
            }
        }
        if (!$dateArray && (int)$date) {
            if (strlen($date) == 6) {
                $dateArray = str_split($date, 2);
            } elseif (strlen($date) == 8) {
                $dateArray = array(
                    substr($date, 0, 4),
                    substr($date, 4, 2),
                    substr($date, 6, 2),
                );
            } elseif (strlen($date) == 2 && $outputType == 'justMonth') {
                $dateMonth = $date;
            } else {
                return false;
            }
        }
        $output = '';
        if (count($dateArray)) {
            $output .= $this->convert($dateArray[2])
                . ' '
                . $class::$months[(int)$dateArray[1] - 1]
                . ' '
                . $this->convert($dateArray[0]);
        } elseif ($dateMonth) {
            $output .= $class::$months[(int)$dateMonth];
        }
        return $output;
    }

    public function nthConvert($digits, $secondType = false)
    {
    }
}
