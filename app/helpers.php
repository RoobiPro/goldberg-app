<?php
use Illuminate\Support\Facades\Schema;


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
