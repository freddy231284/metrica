<?php
/**
 * Compress and zip a file
 */

namespace App;

class Zip
{

    private $fileName;
    private $result;
    private $xmlPath;
    protected $overwrite = false;

    /**
     * Zip constructor.
     * @param $fileName
     * @param null $xmlPath
     */
    public function __construct($fileName, $xmlPath = null)
    {
        $this->fileName = self::getFileNameWithOutExtension($fileName);
        if ($xmlPath) {
            $this->xmlPath = $xmlPath;
        }
    }

    /**
     * Get a Zip file
     * @param string|null $fileXML The content of XML file
     * @return string XML file zipped
     * @throws \Exception If an error occurred
     */
    public function fileCompress($fileXML = null)
    {
        //Create a temporary file with unique file name
        $fileZip = $this->getTemporaryFileName();

        try {
            //create the Zip archive
            $zip = new \ZipArchive();

            //create the zip
            if ($zip->open($fileZip, $this->overwrite ? \ZIPARCHIVE::OVERWRITE : \ZIPARCHIVE::CREATE) !== true) {
                throw new \Exception("Can't open the {$fileZip}.");
            }

            //If XML content is empty then get XML file path
            if (empty($fileXML)) {
                //Path of the XML file
                $filePath = $this->xmlPath . $this->fileName . '.xml';
                //Check if the file XML exists
                if (!file_exists($filePath)) {
                    throw new \Exception("The file path: {$filePath} doesn't exist.");
                }
                //Add the file to zip
                $zip->addFile($filePath, $this->fileName . '.xml');
            } else {
                //Add to the zip file from Xml content called 'fileName'
                $zip->addFromString($this->fileName . '.xml', $fileXML);
            }

            //close the zip -- done!
            $zip->close();

            //Get the file Zip in binary
            $data = file_get_contents($fileZip);

            //The result is the Zipped File
            $this->result = $data;

            //Delete temporary file
            unlink($fileZip);
        } catch (\Exception $e) {
            throw new \Exception($e->getCode() . ': ' . $e->getMessage());
        }

        return $this->result;
    }

    /**
     * Extract a zip file to get the content of the XML file
     * @param string $zipStr Content Zip file in binary
     * @param string $type Type of XML file
     * @return string Content of the XML file zipped
     * @throws \Exception If there is an error
     */
    public function fileExtract($zipStr, $type = 'response')
    {
        $xmlFileName = ($type == 'response') ? "R-{$this->fileName}" : $this->fileName;

        //Create a temporary file with unique file name
        $fileZip = $this->getTemporaryFileName();

        try {
            //Create a Zip file from binary
            $handle = fopen($fileZip, "c");
            if ($handle) {
                //Write the binary to fileZip
                fwrite($handle, $zipStr);
                fclose($handle);

                //Get the content of the XML file zipped, eg: R-20381034071-01-F050-00000010.xml
                $data = file_get_contents("zip://{$fileZip}#{$xmlFileName}.xml");
                if ($data) {
                    //Delete temporary file
                    unlink($fileZip);
                    //Set the content of the response XML file
                    $this->result = $data;
                }
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getCode() . ': ' . $e->getMessage());
        }

        return $this->result;
    }

    /**
     * Get a temporary file name
     * @return string File name
     */
    protected function getTemporaryFileName()
    {
        $fileName = tempnam(sys_get_temp_dir(), "zip{$this->fileName}_");
        return $fileName;
    }

    /**
     * Get the file name without extension
     * @param $fileName File name, eg: 20100019940-01-FF01-00000001.xml
     * @return mixed Return the file name, eg: 20100019940-01-FF01-00000001
     */
    protected static function getFileNameWithOutExtension($fileName)
    {
        $fileName = explode(".", $fileName);
        $fileName = current($fileName);
        return $fileName;
    }

}