<?php
namespace App;

class FileController {

    const VALID_EXTENSIONS = array('csv', 'txt');

    private $baseDir;
    
    public function __construct( $baseDir = "/resources" )
    {
        $this->baseDir = $baseDir;
    }

    /**
     * @return string File name of upload file or empty string on failed upload
     */
    public function upload( $inputName )
    {
        $fileName = $_FILES[$inputName]["name"];
        $serverFileDest = dirname(__DIR__) . $this->baseDir . '/' . basename( $fileName );
        if ( $this->isValidExtension($fileName) && 
            // the file dosnt exists for upload
            !file_exists( $serverFileDest ) )
        {
            // clear existing files
            $this->clear();
            
            if( move_uploaded_file( $_FILES[$inputName]['tmp_name'], $serverFileDest) )
                return $fileName;

            return "";
        }

        return "";
    }

    /**
     * Removes all files on base directory
     */
    public function clear()
    {
        $files = glob( dirname(__DIR__) . $this->baseDir . '/*');
        foreach( $files as $filePath) {
            if ( is_file( $filePath ) ) {
                unlink( $filePath );
            }
        }
    }

    /**
     * check for any uploaded file
     * @return string name of the file or empty string
     */
    public function getFile( )
    {
        $files = glob( dirname(__DIR__) . $this->baseDir . '/*');
        foreach( $files as $filePath) {
            if ( is_file($filePath) ) {
                return basename( $filePath );
            }
        }
        return "";
    }


    private function isValidExtension( $fileName )
    {
        return in_array( pathinfo( $fileName, PATHINFO_EXTENSION ), self::VALID_EXTENSIONS );        
    }

}