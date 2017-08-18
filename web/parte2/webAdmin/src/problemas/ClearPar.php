<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18/08/2017
 * Time: 15:33
 */

namespace App\Controller;


class ClearPar
{
    public function __construct()
    {
    }

    /**
     * Clear string
     * @param $param
     * @return string
     */
    public function build($param)
    {
        $acum = "";
        $result = "";
        for ($i = 0; $i < strlen($param); $i++) {
            $temp = substr($param, $i, 1);

            if ($temp == '(') {
                $acum = $temp;
            } else {
                if ($temp == ')') $acum = $acum . $temp;
            }
            if (strlen($acum) == 2) {
                $result = $result . $acum;
                $acum = "";
            }
        }
        return $result;

    }

}