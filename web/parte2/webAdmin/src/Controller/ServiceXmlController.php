<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18/08/2017
 * Time: 16:16
 */
namespace App\Controller;

use Silex\Application;
use Silex\Controller;
use SimpleXMLElement;

class ServiceXmlController extends Controller
{

    private $monolog;
    private $path_json;
    private $app;

    /**
     * ReportUiController constructor.
     * @param Application $app
     * @param string $channel
     */
    public function __construct(Application $app, string $channel)
    {
        //Define the Monolog Channel
        $this->monolog = $app['monolog.' . $channel];

        $this->path_json = __DIR__ . '/../../resources/json/';
        $this->app = $app;
    }

    public function generateXML(){

        $content = json_decode(file_get_contents($this->path_json . "/employees.json"), true);

        $xml_user_info = new SimpleXMLElement("<?xml version='1.0'?><user_info></user_info>");
        $this->array_to_xml($content,$xml_user_info);
        $xml_file = $xml_user_info->asXML();

        return $xml_file;
    }

    //function defination to convert array to xml
    public function array_to_xml($array, &$xml_user_info) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_user_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }else{
                    $subnode = $xml_user_info->addChild("item$key");
                    $this->array_to_xml($value, $subnode);
                }
            }else {
                $xml_user_info->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

}