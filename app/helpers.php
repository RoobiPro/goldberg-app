<?php
use Illuminate\Support\Facades\Schema;
use App\Models\CsvImport;
use App\Models\Project;
use App\Models\Campaigns\Spatial;

function getRoleName($rolenr) {
  if ($rolenr == 0) {
    return 'Viewer';
  } else if ($rolenr == 1) {
    return 'Editor';
  } else if ($rolenr == 2) {
    return 'Admin';
  } else {
    return 'Role: ' + $rolenr;
  }
};

function formatDBField($field){
  if($field=='id'){
    return 'ID';
  }
  else if(str_contains($field, '_id')||str_contains($field, 'created_at')||str_contains($field, 'updated_at')){
    return false;
  }
  else{
    $field=str_replace('_', ' ', $field);
    $field=str_replace('utm', 'UTM', $field);
    return ucwords($field);
  }
}

function getTableHeaders($tablename){
  $columns = Schema::getColumnListing($tablename); // users table
  $header_array=[];
  $index = 0;
  foreach($columns as $column) {
      $myObject = new stdClass();
      $myObject->text=formatDBField($columns[$index]);
      if($myObject->text==false){
        $index++;
        continue;
      }
      $myObject->align = "start";
      $myObject->value=$columns[$index];
      array_push($header_array,$myObject);
      $index++;
  }
  return $header_array;
};

function checkForImport($data){
  if(CsvImport::where('table_type', $data[0])
                    ->where('file_name', $data[1])
                    ->where('bytes', $data[2])
                    ->whereNull('deleted_at')
                    ->first()){
    return true;
  }
  else{
    return false;
  }
}

function checkSpatialExists($data){
  if(Spatial::where('attachment', $data[1])
                    ->where('bytes', $data[2])
                    ->where('full_path', $data[0])
                    ->first()){
      return true;
  }
  else{
    return false;
  }
}

function checkNameExists($data){
  if(Spatial::where('attachment', $data[1])
                    ->whereNotIn('bytes', [$data[2],0])
                    ->where('full_path', $data[0])
                    ->first()){
      return true;
  }
  else{
    return false;
  }
}

function convert_accent($string){
    return htmlentities($string, ENT_COMPAT, 'UTF-8');
}

function getDrillingID($file){
  $handle = fopen($file, "r");
  $row=0;
  while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    if($row==0){
        $row++;
        continue;
        // SKIP HEADER ROW
      }
      $name = $data[0];
      break;
  }
  return $name;
}

function getProjectId($file){
  $handle = fopen($file, "r");
  $row=0;
  while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    if($row==0){
        $row++;
        continue;
        // SKIP HEADER ROW
      }
      $name = $data[0];
      break;
  }

  $project = Project::where('name', $name)->first();
  if(!$project){
    false;
  }
  else{
    return $project->id;
  }
}
