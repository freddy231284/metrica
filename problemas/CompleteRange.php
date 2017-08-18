<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18/08/2017
 * Time: 15:33
 */

namespace App\Controller;


class CompleteRange
{
    public function __construct()
    {
    }

    /**
     * Complete range of numbers
     * @param $param
     * @return array
     */
    public function build($param)
    {
        $numFirst = $param[0];
        $numLast = $param[count($param) - 1];
        $result = [];
        for ($i = $numFirst; $i <= $numLast; $i++) {
            $result[] = $i;
        }
        return $result;
    }

}