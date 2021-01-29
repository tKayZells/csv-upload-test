<?php

namespace App;

use DateTime;
use Exception;
use SplFileObject;

class CSVFileProcessor {

    protected $file;
    protected $delimiter;

    public function __construct( $fileName, $baseDir = "/resources", $delimiter = ";" )
    {
        if ( !file_exists( dirname(__DIR__) . $baseDir . '/' . $fileName ) ) throw new Exception("File not found");

        // default mode is read
        $this->file = new SplFileObject( dirname(__DIR__) . $baseDir . '/' . $fileName);
        $this->delimiter = $delimiter;
    }

    /**
     * return generator of all matching id rows
     */
    public function findById( $id )
    {
        foreach( $this->getCSVIterator() as $row )
        {
            // The first element is always the row id
            if( $id == $row['row'][0])
            {
                yield $row;
            }
        }
    }

    /**
     * @return array where the keys are the duplicate ids and values are the row number in the uploaded file
     */
    public function findDuplicateData()
    {
        $temp = [];
        $duplicateRows = [];
        foreach( $this->getCSVIterator() as $row )
        {
            if( array_key_exists ( $row['row'][0], $temp ) )
            {
                $duplicateRows[ $row['row'][0] ] []= $row['row_number'] ;
            }
            $temp[ $row['row'][0] ] = 1;
        }
        return $duplicateRows;
    }

    /**
     * return generator of incorrect row data
     */
    public function findWrongData()
    {
        foreach( $this->getCSVIterator() as $row )
        {
            list( $id, $zona, $fecha_desde, $fecha_hasta ) = $row['row'];
            // if init date is greater than end date, row is incorrect
            $validDate = new DateTime($fecha_desde) < new DateTime($fecha_hasta);
            if(!$validDate)
            {
                yield $row;
            }
        }
    }

    /**
     * return generator for each row of the csv
     */
    private function getCSVIterator()
    {
        // ignoring the first line where the headers are
        $line = 1;
        $this->file->fgetcsv( $this->delimiter );

        while(!$this->file->eof())
        {
            $line++;
            yield [ 'row' => $this->file->fgetcsv( $this->delimiter ), 'row_number' => $line ];
        }
    }

}