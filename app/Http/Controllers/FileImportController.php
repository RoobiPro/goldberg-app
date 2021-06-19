<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaigns\Spatial;
use App\Models\Campaigns\HandSample;
use App\Models\Campaigns\Drilling;
use App\Models\Campaigns\Well;
use App\Models\SampleLists\DrillingSampleList;
use App\Models\SampleLists\WellSampleList;
use App\Models\Data\Alteration;
use App\Models\Data\DrillingAssay;
use App\Models\Data\WellAssay;
use App\Models\Data\DrillingMineralization;
use App\Models\Data\Lithology;
use App\Models\Data\Survey;
use App\Models\Project;
use App\Models\CsvImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;




class FileImportController extends Controller
{
    //
    public function getImports($id){
      $imports = CsvImport::where('project_id', $id)->get();
      $index=0;
      foreach ($imports as $import) {
        $imports[$index]->import_date = Carbon::parse($import->import_date)->format('d.m.Y');
        $index++;
      }
      return $imports;
    }

    public function displaySpatial($id){
      $spatial = Spatial::find($id);
      $file = $spatial->full_path.$spatial->attachment;
      return Storage::response($file);
    }

    public function downloadSpatial($id){
      $spatial = Spatial::find($id);
      $file = $spatial->full_path.$spatial->attachment;
      return Storage::download($file);
    }

    public function deleteSpatial($id){
      $spatial = Spatial::find($id);
      Storage::delete($spatial->full_path.$spatial->attachment);
      $spatial->delete();
    }

    public function uploadSpatial(Request $request){
      // return $request->route('id');
      if(!$request->file('spatial')){
        return response()->json([
            'type'    => 'red',
            'message'   => 'No spatial was uploaded!',
        ]);
      }
      $file_path = 'project-spatials/project/'.(string)$request->route('id').'/';
      $filedata=[];

      array_push($filedata, $file_path, $request->file('spatial')->getClientOriginalName(), $request->file('spatial')->getSize());

      if(checkSpatialExists($filedata)){
        return response()->json([
            'type'    => 'red',
            'message'   => 'Spatial already in project!',
        ]);
      }

      if(checkNameExists($filedata)){
        return response()->json([
            'type'    => 'red',
            'message'   => 'A file with this name already exists, change the file name!',
        ]);
      }


      // $full_path = 'folder/folder/' . $name;
      Storage::putFileAs($file_path, $request->file('spatial'), $request->file('spatial')->getClientOriginalName());

      $spatial = new Spatial;
      $spatial->project_id = $request->route('id');
      $spatial->attachment = $request->file('spatial')->getClientOriginalName();
      $spatial->full_path = $file_path;
      $spatial->bytes = $request->file('spatial')->getSize();
      $spatial->file_type = $request->file('spatial')->getClientOriginalExtension();
      $spatial->save();


      return response()->json([
          'type'    => 'green',
          'message'   => 'Spatial successfully uploaded!',
      ]);

    }

    public function createImport($file, $type){
      $projectexists = getProjectId($file);
      if(!$projectexists){
        return false;
      }
      $filenameWithExt = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $CsvImport = new CsvImport;
      $CsvImport->project_id = $projectexists;
      $CsvImport->table_type = $type;
      $CsvImport->import_date = now();
      $CsvImport->file_name = $filenameWithExt;
      $CsvImport->bytes = $file->getSize();
      $CsvImport->save();
      return $CsvImport;
    }

    public function createImportWellData($file, $type){
      $drillingID = getDrillingID($file);
      $drilling = Well::where('well_code', $drillingID)->first();
      if (empty($drilling)) {return false;}
      $filenameWithExt = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $CsvImport = new CsvImport;
      $CsvImport->project_id = $drilling->project_id;
      $CsvImport->table_type = $type;
      $CsvImport->import_date = now();
      $CsvImport->file_name = $filenameWithExt;
      $CsvImport->bytes = $file->getSize();
      $CsvImport->save();
      return $CsvImport;
    }

    public function createImportData($file, $type){
      $drillingID = getDrillingID($file);
      $drilling = Drilling::where('drilling_code', $drillingID)->first();
      if (empty($drilling)) {return false;}
      $filenameWithExt = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $CsvImport = new CsvImport;
      $CsvImport->project_id = $drilling->project_id;
      $CsvImport->table_type = $type;
      $CsvImport->import_date = now();
      $CsvImport->file_name = $filenameWithExt;
      $CsvImport->bytes = $file->getSize();
      $CsvImport->save();
      return $CsvImport;
    }

    public function storeDrillingSurvey(Request $req){
      $filedata=[];
      array_push($filedata, 'survey', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImportData($req->file('csvfile'), 'survey');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No drilling with this ID found!"], 200);
      }

      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $surveyArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $drilling = Drilling::where('drilling_code', $data[0])->get();
        if (!$drilling->isEmpty()){
            $drilling = $drilling->first();
        }
        else{
          foreach ($surveyArray as $survey) {
            $survey->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => "Project with this name does not exist", "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $survey = Survey::create([
            'suveryable_type' => get_class($drilling),
            'suveryable_id' => $drilling->id,
            'from' => $data[3],
            'to' => $data[4],
            'azimuth' => $data[1],
            'dip' => $data[2],
            'csv_import_id' => $CsvImport->id
          ]);

            array_push($surveyArray, $survey);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($surveyArray as $survey) {
            $survey->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeDrillingMiniralization(Request $req){
      $filedata=[];
      array_push($filedata, 'drilling_mineralization', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImportData($req->file('csvfile'), 'drilling_mineralization');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No drilling with this ID found!"], 200);
      }
      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $lithArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $drilling = Drilling::where('drilling_code', $data[0])->get();
        if (!$drilling->isEmpty()){
            $drilling = $drilling->first();
        }
        else{
          foreach ($lithArray as $lith) {
            $lith->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => "Project with this name does not exist", "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $lith = DrillingMineralization::create([
            'drilling_id' => $drilling->id,
            'from' => $data[1],
            'to' => $data[2],
            'intensity' => $data[4],
            'mineralization_code' => $data[3],
            'mineralization_description' => $data[5],
            'csv_import_id' => $CsvImport->id
          ]);

            array_push($lithArray, $lith);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($lithArray as $lith) {
            $lith->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeDrillingLithology(Request $req){
      $filedata=[];
      array_push($filedata, 'lithology', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImportData($req->file('csvfile'), 'lithology');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No drilling with this ID found!"], 200);
      }

      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $lithArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $drilling = Drilling::where('drilling_code', $data[0])->get();
        if (!$drilling->isEmpty()){
            $drilling = $drilling->first();
        }
        else{
          foreach ($lithArray as $lith) {
            $lith->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $lith = Lithology::create([
            'lithologyable_type' => get_class($drilling),
            'lithologyable_id' => $drilling->id,
            'from' => $data[1],
            'to' => $data[2],
            'rock_code' => $data[3],
            'rock_description' => $data[4],
            'geological_unit' => $data[5],
            'csv_import_id' => $CsvImport->id
          ]);

            array_push($lithArray, $lith);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($lithArray as $lith) {
            $lith->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeDrillingAssay(Request $req){
      $filedata=[];
      array_push($filedata, 'drilling_assay', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImportData($req->file('csvfile'), 'drilling_assay');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No drilling with this ID found!"], 200);
      }

      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $assayArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $drilling = Drilling::where('drilling_code', $data[0])->get();
        if (!$drilling->isEmpty()){
            $drilling = $drilling->first();
        }
        else{
          foreach ($assayArray as $assay) {
            $assay->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => "Project with this name does not exist", "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $assay = DrillingAssay::create([
            'drilling_id' => $drilling->id,
            'project_id' => $drilling->project_id,
            'sample_code' => $data[1],
            'from' => $data[2],
            'to' => $data[3],
            'certificate' => str_replace('<', '-', $data[4]),
            'element_Ag' => str_replace('<', '-', $data[5]),
            'element_Al' => str_replace('<', '-', $data[6]),
            'element_As' => str_replace('<', '-', $data[7]),
            'element_Au' => str_replace('<', '-', $data[8]),
            'element_B' => str_replace('<', '-', $data[9]),
            'element_Ba' => str_replace('<', '-', $data[10]),
            'element_Be' => str_replace('<', '-', $data[11]),
            'element_Bi' => str_replace('<', '-', $data[12]),
            'element_Ca' => str_replace('<', '-', $data[13]),
            'element_Cd' => str_replace('<', '-', $data[14]),
            'element_Ce' => str_replace('<', '-', $data[15]),
            'element_Co' => str_replace('<', '-', $data[16]),
            'element_Cr' => str_replace('<', '-', $data[17]),
            'element_Cs' => str_replace('<', '-', $data[18]),
            'element_Cu' => str_replace('<', '-', $data[19]),
            'element_Fe' => str_replace('<', '-', $data[20]),
            'element_Ga' => str_replace('<', '-', $data[21]),
            'element_Ge' => str_replace('<', '-', $data[22]),
            'element_Hf' => str_replace('<', '-', $data[23]),
            'element_Hg' => str_replace('<', '-', $data[24]),
            'element_In' => str_replace('<', '-', $data[25]),
            'element_K' => str_replace('<', '-', $data[26]),
            'element_La' => str_replace('<', '-', $data[27]),
            'element_Li' => str_replace('<', '-', $data[28]),
            'element_Mg' => str_replace('<', '-', $data[29]),
            'element_Mn' => str_replace('<', '-', $data[30]),
            'element_Mo' => str_replace('<', '-', $data[31]),
            'element_Na' => str_replace('<', '-', $data[32]),
            'element_Nb' => str_replace('<', '-', $data[33]),
            'element_Ni' => str_replace('<', '-', $data[34]),
            'element_P' => str_replace('<', '-', $data[35]),
            'element_Pb' => str_replace('<', '-', $data[36]),
            'element_Rb' => str_replace('<', '-', $data[37]),
            'element_Re' => str_replace('<', '-', $data[38]),
            'element_S' => str_replace('<', '-', $data[39]),
            'element_Sb' => str_replace('<', '-', $data[40]),
            'element_Sc' => str_replace('<', '-', $data[41]),
            'element_Se' => str_replace('<', '-', $data[42]),
            'element_Sn' => str_replace('<', '-', $data[43]),
            'element_Sr' => str_replace('<', '-', $data[44]),
            'element_Ta' => str_replace('<', '-', $data[45]),
            'element_Te' => str_replace('<', '-', $data[46]),
            'element_Th' => str_replace('<', '-', $data[47]),
            'element_Ti' => str_replace('<', '-', $data[48]),
            'element_Tl' => str_replace('<', '-', $data[49]),
            'element_U' => str_replace('<', '-', $data[50]),
            'element_V' => str_replace('<', '-', $data[51]),
            'element_W' => str_replace('<', '-', $data[52]),
            'element_Y' => str_replace('<', '-', $data[53]),
            'element_Zn' => str_replace('<', '-', $data[54]),
            'element_Zr' => str_replace('<', '-', $data[55]),
            'csv_import_id' => $CsvImport->id
          ]);

            array_push($assayArray, $assay);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($assayArray as $assay) {
            $assay->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeDrillingAlteration(Request $req){

      $filedata=[];
      array_push($filedata, 'drilling_alteration', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImportData($req->file('csvfile'), 'drilling_alteration');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No drilling with this ID found!"], 200);
      }

      // $CsvImport = $this->createImport($req->file('csvfile'), 'drilling_alteration');
      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $altArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $drilling = Drilling::where('drilling_code', $data[0])->get();
        if (!$drilling->isEmpty()){
            $drilling = $drilling->first();
        }
        else{
          foreach ($altArray as $alt) {
            $alt->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $alt = Alteration::create([
            'drilling_id' => $drilling->id,
            'intensity' => $data[5],
            'from' => $data[1],
            'to' => $data[2],
            'alteration_code' => $data[3],
            'alteration_description' => utf8_encode($data[4]),
            'csv_import_id' => $CsvImport->id
          ]);
            array_push($altArray, $alt);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($altArray as $alt) {
            $alt->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);

            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeWellAssay(Request $req){

      $filedata=[];
      array_push($filedata, 'well_assay', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImportWellData($req->file('csvfile'), 'well_assay');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No well with this ID found!"], 200);
      }
      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $assayArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $well = Well::where('well_code', $data[0])->get();
        if (!$well->isEmpty()){
            $well = $well->first();
        }
        else{
          foreach ($assayArray as $assay) {
            $assay->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $assay = WellAssay::create([
            'well_id' => $well->id,
            'sample' => $data[1],
            'analysis_code' => $data[2],
            'laboratory' => $data[3],
            'certificate_number' => $data[4],
            'date_sent' => Carbon::createFromFormat('d-m-Y', $data[5])->format('Y-m-d'),
            'date_received' => Carbon::createFromFormat('d-m-Y', $data[6])->format('Y-m-d'),
            'from' => $data[7],
            'to' => $data[8],
            'element_Fe' => $data[9],
            'csv_import_id' => $CsvImport->id
          ]);

            array_push($assayArray, $assay);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($assayArray as $assay) {
            $assay->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeWellLithology(Request $req){
      $filedata=[];
      array_push($filedata, 'lithology', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImportWellData($req->file('csvfile'), 'lithology');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No well with this ID found!"], 200);
      }

      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $lithArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $well = Well::where('well_code', $data[0])->get();
        if (!$well->isEmpty()){
            $well = $well->first();
        }
        else{
          foreach ($lithArray as $lith) {
            $lith->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $lith = Lithology::create([
            'lithologyable_type' => get_class($well),
            'lithologyable_id' => $well->id,
            'from' => $data[1],
            'to' => $data[2],
            'rock_code' => $data[3],
            'rock_description' => $data[4],
            'geological_unit' => $data[5],
            'csv_import_id' => $CsvImport->id
          ]);

            array_push($lithArray, $lith);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($lithArray as $lith) {
            $lith->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeWellSurvey(Request $req){
      $filedata=[];
      array_push($filedata, 'survey', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImportWellData($req->file('csvfile'), 'survey');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No well with this ID found!"], 200);
      }

      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $surveyArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $well = Well::where('well_code', $data[0])->get();
        if (!$well->isEmpty()){
            $well = $well->first();
        }
        else{
          foreach ($surveyArray as $survey) {
            $survey->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $survey = Survey::create([
            'suveryable_type' => get_class($well),
            'suveryable_id' => $well->id,
            'from' => $data[1],
            'to' => $data[2],
            'azimuth' => $data[4],
            'dip' => $data[3],
            'csv_import_id' => $CsvImport->id
          ]);

            array_push($surveyArray, $survey);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($surveyArray as $survey) {
            $survey->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeWell(Request $req){

          $filedata=[];
          array_push($filedata, 'well', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
          if(checkForImport($filedata)){
            return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
          }
          if(!$req->hasFile('csvfile')){
            return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
          }
          $CsvImport = $this->createImport($req->file('csvfile'), 'well');
          if(!$CsvImport){
            return response()->json([ "success" => false, "type" => 'red', "message" => "No project with this name found!"], 200);
          }

          // return $CsvImport;
          $row=0;
          $handle = fopen($req->file('csvfile'), "r");
          $wellArray = [];
          while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if($row==0){
                $row++;
                continue;
                // SKIP HEADER ROW
              }
              $projects = Project::where('name', $data[0])->get();
            if (!$projects->isEmpty()){
                $project = $projects->first();
            }
            else{
              foreach ($wellArray as $well) {
                $well->delete();
              }
              $CsvImport->success = false;
              $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
              $CsvImport->save();
              $CsvImport->delete();
              return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
            }
            try{
              $well = Well::create([
                  'project_id' => $project->id,
                  'well_code' => $data[1],
                  'type' =>  $data[8],
                  'utm_x' => $data[2],
                  'utm_y' => $data[3],
                  'utm_z' => $data[4],
                  // 'date' => $data[4],
                  'dip' => $data[6],
                  'length' => $data[5],
                  'azimuth' => $data[7],
                  'well_campaign' => $data[9],
                  'csv_import_id' => $CsvImport->id
              ]);
                array_push($wellArray, $well);
            }
            catch(\Illuminate\Database\QueryException $ex){
              foreach ($wellArray as $well) {
                $well->delete();
              }
              $CsvImport->success = false;
              $CsvImport->error_description = $ex->getMessage();
              $CsvImport->save();
              $CsvImport->delete();
              return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
                // return $ex->getMessage();
            }
            $row++;
          }
          $CsvImport->success = true;
          $CsvImport->data_lines = $row-1;
          $CsvImport->save();
          return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeHandSample(Request $req){
      $filedata=[];
      array_push($filedata, 'handsample', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'handsample');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No project with this name found!"], 200);
      }
        // return $CsvImport;
      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $handsamplesArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $projects = Project::where('name', $data[0])->get();
        if (!$projects->isEmpty()){
            $project = $projects->first();
        }
        else{
          foreach ($handsamplesArray as $handsample) {
            $handsample->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
        }
        try{
          $handsample = HandSample::create([
              'project_id' => $project->id,
              'hand_sample_code' => $data[1],
              'hand_sample_campaign' => $data[6],
              'type' =>  $data[5],
              'utm_x' => $data[2],
              'utm_y' => $data[3],
              'utm_z' => $data[4],
              'csv_import_id' => $CsvImport->id
          ]);
            array_push($handsamplesArray, $handsample);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($handsamplesArray as $handsample) {
            $handsample->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);
    }

    public function storeDrilling(Request $req){

      // Check CSV Filetype!
      // $fileType = $req->filetype;
      // if (!$req->filetype){
      //   return response()->json([ "success" => false, "msg" => 'Upload Data has no Filetype!'], 200);
      // }
      $filedata=[];
      array_push($filedata, 'drilling', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }
      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'drilling');
      if(!$CsvImport){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No project with this name found!"], 200);
      }

        // return $CsvImport;
      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $drillingsArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $projects = Project::where('name', $data[0])->get();
        if (!$projects->isEmpty()){
            $project = $projects->first();
        }
        else{
          foreach ($drillingsArray as $drillingsArray) {
            $drillingsArray->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();

          return response()->json([ "success" => false, "type" => 'red', "message" => "Project with this name does not exist", "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $drilling = Drilling::create([
              'project_id' => $project->id,
              'drilling_code' => $data[1],
              'type' =>  $data[9],
              'utm_x' => $data[3],
              'utm_y' => $data[4],
              'utm_z' => $data[5],
              'start_date' => now(),
              'end_date' => now(),
              'dip' => $data[7],
              'length' => $data[8],
              'azimuth' => $data[6],
              'csv_import_id' => $CsvImport->id
            ]);
            array_push($drillingsArray, $drilling);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($drillingsArray as $drillingsArray) {
            $drillingsArray->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);

      // return "Import successful";
    }

    public function storeDrillingSamplelist(Request $req){
      $filedata=[];
      array_push($filedata, 'drilling_sample_list', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());

      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }

      $SampleCode = getDrillingID($req->file('csvfile'));
      $DrillingAssay = DrillingAssay::where('sample_code', $SampleCode)->first();
      if(!$DrillingAssay){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No Drilling with this code found!"], 200);
      }
      $filenameWithExt = $req->file('csvfile')->getClientOriginalName();
      $extension = $req->file('csvfile')->getClientOriginalExtension();
      $CsvImport = new CsvImport;
      $CsvImport->project_id = $DrillingAssay->drilling->project_id;
      $CsvImport->table_type = 'drilling_sample_list';
      $CsvImport->import_date = now();
      $CsvImport->file_name = $filenameWithExt;
      $CsvImport->bytes = $req->file('csvfile')->getSize();
      $CsvImport->save();

      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }

      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $SampleListArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $DrillingAssay = DrillingAssay::where('sample_code', $data[0])->get();
        if (!$DrillingAssay->isEmpty()){
            $DrillingAssay = $DrillingAssay->first();
        }
        else{
          foreach ($SampleListArray as $SampleListArray) {
            $SampleListArray->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();

          return response()->json([ "success" => false, "type" => 'red', "message" => "Project with this name does not exist", "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $SampleList = DrillingSampleList::create([
              'drilling_id' => $DrillingAssay->drilling_id,
              'project_id' => $DrillingAssay->project_id,
              'sample_code' => $data[0],
              'from' =>  $data[1],
              'to' => $data[2],
              'sample_type' => $data[3],
              'weight' => $data[4],
              'csv_import_id' => $CsvImport->id,
            ]);
            array_push($SampleListArray, $SampleList);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($SampleListArray as $SampleList) {
            $SampleList->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);

    }

    public function storeWellSamplelist(Request $req){
      $filedata=[];
      array_push($filedata, 'well_sample_list', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());

      if(checkForImport($filedata)){
        return response()->json([ "success" => false, "type" => 'red', "message" => "This file has been already imported!"], 200);
      }

      $WellCode = getDrillingID($req->file('csvfile'));
      $Well = Well::where('well_code', $WellCode)->first();
      if(!$Well){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No Well with this code found!"], 200);
      }
      $filenameWithExt = $req->file('csvfile')->getClientOriginalName();
      $extension = $req->file('csvfile')->getClientOriginalExtension();
      $CsvImport = new CsvImport;
      $CsvImport->project_id = $Well->project_id;
      $CsvImport->table_type = 'well_sample_list';
      $CsvImport->import_date = now();
      $CsvImport->file_name = $filenameWithExt;
      $CsvImport->bytes = $req->file('csvfile')->getSize();
      $CsvImport->save();

      if(!$req->hasFile('csvfile')){
        return response()->json([ "success" => false, "type" => 'red', "message" => "No csv file was passed"], 200);
      }

      $row=0;
      $handle = fopen($req->file('csvfile'), "r");
      $SampleListArray = [];
      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if($row==0){
            $row++;
            continue;
            // SKIP HEADER ROW
          }
          $Well = Well::where('well_code', $data[0])->get();
        if (!$Well->isEmpty()){
            $Well = $Well->first();
        }
        else{
          foreach ($SampleListArray as $SampleListArray) {
            $SampleListArray->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = "error-data-row-number: ". (string)($row+1);
          $CsvImport->save();
          $CsvImport->delete();

          return response()->json([ "success" => false, "type" => 'red', "message" => "Project with this name does not exist", "error_line" => $row+1 ,"error_data" => $data], 200);
        }
        try{
          $SampleList = WellSampleList::create([
              'well_id' => $Well->id,
              'project_id' => $Well->project_id,
              'sample_code' => $data[1],
              'from' =>  $data[2],
              'to' => $data[3],
              'sample_type' => $data[4],
              'weight' => $data[5],
              'csv_import_id' => $CsvImport->id,
            ]);
            array_push($SampleListArray, $SampleList);
        }
        catch(\Illuminate\Database\QueryException $ex){
          foreach ($SampleListArray as $SampleList) {
            $SampleList->delete();
          }
          $CsvImport->success = false;
          $CsvImport->error_description = $ex->getMessage();
          $CsvImport->save();
          $CsvImport->delete();
          return response()->json([ "success" => false, "type" => 'red', "message" => $ex->getMessage(), "error_line" => $row+1 ,"error_data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->data_lines = $row-1;
      $CsvImport->save();
      return response()->json([ "success" => true, "type" => 'green', "message" => 'Import successful'], 200);

    }

    public function delete($id){

      $CsvImport = CsvImport::find($id);
      if ( is_null($CsvImport) ) {
        return response()->json([ "success" => false, "type" =>  "red" ,"text" => "No import with this ID"], 200);

      }
      $type = $CsvImport->table_type;
      $toDelete = $CsvImport->$type;
      foreach ($toDelete as $delete) {
          $delete->delete();
      }
      $CsvImport->delete();
      return response()->json([ "success" => true, "type" =>  "green" ,"text" => "Import Data deleted!"], 200);

    }
}
