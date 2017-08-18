<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18/08/2017
 * Time: 15:33
 */

namespace App\Controller;


class ChangeString
{
    public function __construct()
    {
    }

    /**
     * Change string
     * @param $param
     * @return string
     */
    public function build($param)
    {
        $arrayAbc = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'ñ', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

        $result = '';
        for ($i = 0; $i < strlen($param); $i++) {
            $temp = substr($param, $i, 1);
            $chkUpper = ctype_upper($temp);
            $temp = strtolower($temp);

            if (in_array($temp, $arrayAbc)) {
                $key = array_search($temp, $arrayAbc);
                if (!array_key_exists($key + 1, $arrayAbc)) $key = -1;
                $temp = $chkUpper ? strtoupper($arrayAbc[$key + 1]) : $arrayAbc[$key + 1];
            }
            $result = $result . $temp;
        }
        return $result;
    }

}