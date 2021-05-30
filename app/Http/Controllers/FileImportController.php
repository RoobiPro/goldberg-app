<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaigns\Drilling;


class FileImportController extends Controller
{
    //
    public function index(Request $req){
      if($req->hasFile('csvfile')){
        $filenameWithExt = $req->file('csvfile')->getClientOriginalName();
        $extension = $req->file('csvfile')->getClientOriginalExtension();

        $row=0;
        $handle = fopen($req->file('csvfile'), "r");
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
              if($row==0){
                $row++;
                continue;
              }
              Drilling::create([
                  'project_id' => 1,
                  'drilling_code' => $data[1],
                  'type' =>  $data[9],
                  'utm_x' => $data[3],
                  'utm_y' => $data[4],
                  'utm_z' => $data[5],
                  'start_date' => now(),
                  'end_date' => now(),
                  'dip' => $data[7],
                  'length' => $data[8],
                  'azimuth' => $data[6]
              ]);
              return $data[1];
                $num = count($data);
                return $num;
                // echo "<p> $num Felder in Zeile $row: <br /></p>\n";
                $row++;
                foreach(range(1, 100, $num) as $data) {
                  echo $data;
              }
            }
        // return response()->json([ "success" => true, "namewithext" => $filenameWithExt, 'extension' => $data[0] ], 200);
        // $row=0;
        // if (($handle = fopen("test.csv", "r")) !== FALSE) {
        //     while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        //         $num = count($data);
        //         echo "<p> $num Felder in Zeile $row: <br /></p>\n";
        //         $row++;
        //         for ($c=0; $c < $num; $c++) {
        //             echo $data[$c] . "<br />\n";
        //         }
        //     }
        //     fclose($handle);
        // }

        // return response()->json([ "success" => true, "namewithext" => $filenameWithExt, 'extension' => $extension ], 200);
      }
    }
}
