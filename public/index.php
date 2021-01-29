<?php

use App\CSVFileProcessor;
use App\FileController;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$fc = new FileController();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CSV Verification</title>
    </head>
    <body>
    
        <form action="/" method="post" enctype="multipart/form-data">
            Select CSV to upload:
            <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv, text/plain">
            <input type="hidden" name="upload" value="1" />
            <input type="submit" value="Process Data" name="submit">
        </form>
        <?php 
            
            $serveFileName = $fc->getFile();
            if( !empty($serveFileName) )
            {   
                include dirname(__DIR__)."/src/view/Operations.php";
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' )
            {

                // Wrong data fetch
                if( !empty($_POST["fetch_incorrect_data_action"]) )
                {   
                    if ( !empty($serveFileName) )
                    {
                        $csvFP = new CSVFileProcessor($serveFileName);
                        ?>
                            <table>
                                <thead>
                                    <th>id</th>
                                    <th>zona</th>
                                    <th>fecha_desde</th>
                                    <th>fecha_hasta</th>
                                    <th>file_line</th>
                                </thead>
                                <tbody>
                        <?php
                        foreach( $csvFP->findWrongData() as $row ) 
                        {
                            ?>
                                <tr>
                                    <td><?php echo $row['row'][0]; ?></td>
                                    <td><?php echo $row['row'][1]; ?></td>
                                    <td><?php echo $row['row'][2]; ?></td>
                                    <td><?php echo $row['row'][3]; ?></td>
                                    <td><?php echo $row['row_number']; ?></td>
                                </tr>
                            <?php
                        }
                        ?>
                                </tbody>
                            </table>
                        <?php
                    }
                }

                // Duplicate IDs
                if( !empty($_POST["fetch_duplicate_data"]))
                {
                    if ( !empty($serveFileName) )
                    {
                        $csvFP = new CSVFileProcessor($serveFileName);
                        $data = $csvFP->findDuplicateData();
                        ?>
                            <table>
                                <thead>
                                    <th>id</th>
                                    <th>file_lines</th>
                                </thead>
                                <tbody>
                        <?php
                        foreach($data as $id => $rows)
                        {
                            
                            ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><pre><?php echo implode(",\n", $rows); ?></pre></td>
                                </tr>
                            <?php
                        }
                        ?>
                                </tbody>
                            </table>
                        <?php
                    }
                }

                // file upload proccess
                if( !empty($_POST["upload"]) )
                {
                    $fileName = $fc->upload("fileToUpload");
                    include dirname(__DIR__)."/src/view/Operations.php";
                }

                // row lookup proccess
                if( !empty($_POST['lookup']) )
                {
                    $csvFP = new CSVFileProcessor($serveFileName);
                    ?>
                        <table>
                            <thead>
                                <th>id</th>
                                <th>zona</th>
                                <th>fecha_desde</th>
                                <th>fecha_hasta</th>
                                <th>file_line</th>
                            </thead>
                            <tbody>
                    <?php
                    foreach( $csvFP->findById( $_POST['csv_id']) as $row )
                    {
                        ?>
                        <tr>
                            <td><?php echo $row['row'][0]; ?></td>
                            <td><?php echo $row['row'][1]; ?></td>
                            <td><?php echo $row['row'][2]; ?></td>
                            <td><?php echo $row['row'][3]; ?></td>
                                    <td><?php echo $row['row_number']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                            </tbody>
                        </table>
                    <?php
                }
            }
        
        ?>
    </body>
</html>