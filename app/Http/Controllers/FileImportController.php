<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaigns\Spatial;
use App\Models\Campaigns\HandSample;
use App\Models\Campaigns\Drilling;
use App\Models\Campaigns\Well;
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

          if(auth('sanctum')->user()->avatar != "default_avatar.png"){

              Storage::disk('user_avatars')->delete(auth('sanctum')->user()->avatar);
          }


          // processing the uploaded image
          // return response()->json($this->generateRandomString(), 200);
          //
          $avatar_name = $this->generateRandomString().'.'.$request->file('avatar')->getClientOriginalExtension();

          $avatar_path = $request->file('spatial')->storeAs('',$avatar_name, 'user_avatars');

          // Update user's avatar column on 'users' table
          $profile = User::find(auth('sanctum')->user()->id);
          $profile->avatar = $avatar_path;

          // return response()->json($avatar_path, 200);

          if($profile->save()){
              return response()->json([
                  'status'    =>  'success',
                  'message'   =>  'Profile Photo Updated!',
                  'avatar_url'=>  url('storage/user-avatar/'.$avatar_path),
                  'avatar' => $profile->avatar
              ]);
          }else{
              return response()->json([
                  'status'    => 'failure',
                  'message'   => 'failed to update profile photo!',
                  'avatar_url'=> NULL
              ]);
          }

      }

    //     return response()->json([
    //         'status'    => 'failure',
    //         'message'   => 'No image file uploaded!',
    //         'avatar_url'=> NULL
    //     ]);
    // }

    public function createImport($file, $type){
      $filenameWithExt = $file->getClientOriginalName();
      $extension = $file->getClientOriginalExtension();
      $CsvImport = new CsvImport;
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
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'survey');
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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function storeDrillingMiniralization(Request $req){
      $filedata=[];
      array_push($filedata, 'drilling_mineralization', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'drilling_mineralization');
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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function storeDrillingLithology(Request $req){
      $filedata=[];
      array_push($filedata, 'lithology', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'lithology');
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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function storeDrillingAssay(Request $req){
      $filedata=[];
      array_push($filedata, 'drilling_assay', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'drilling_assay');
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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
        }
        try{
          $assay = DrillingAssay::create([
            'drilling_id' => $drilling->id,
            'sample' => $data[1],
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function storeDrillingAlteration(Request $req){
      $filedata=[];
      array_push($filedata, 'drilling_alteration', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'drilling_alteration');
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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function storeWellAssay(Request $req){
      $filedata=[];
      array_push($filedata, 'well_assay', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'well_assay');
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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function storeWellLithology(Request $req){
      $filedata=[];
      array_push($filedata, 'lithology', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'lithology');
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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function storeWellSurvey(Request $req){
      $filedata=[];
      array_push($filedata, 'survey', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'survey');
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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function storeWell(Request $req){

          $filedata=[];
          array_push($filedata, 'well', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
          if(checkForImport($filedata)){
            return "This file has been already imported";
          }
          if(!$req->hasFile('csvfile')){
            return "No csv file was passed";
          }
          $CsvImport = $this->createImport($req->file('csvfile'), 'well');

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
          $CsvImport->save();
          return "Import successful";
    }

    public function storeHandSample(Request $req){

      $filedata=[];
      array_push($filedata, 'handsample', $req->file('csvfile')->getClientOriginalName(), $req->file('csvfile')->getSize());
      if(checkForImport($filedata)){
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'handsample');

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
      $CsvImport->save();
      return "Import successful";
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
        return "This file has been already imported";
      }
      if(!$req->hasFile('csvfile')){
        return "No csv file was passed";
      }
      $CsvImport = $this->createImport($req->file('csvfile'), 'drilling');

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
          return response()->json([ "success" => false, "msg" => "Project with this name does not exist", "error-line" => $row+1 ,"error-data" => $data], 200);
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
          return response()->json([ "success" => false, "msg" => $ex->getMessage(), "error-line" => $row+1 ,"error-data" => $data], 200);
            // return $ex->getMessage();
        }
        $row++;
      }
      $CsvImport->success = true;
      $CsvImport->save();
      return "Import successful";
    }

    public function delete($id){

      $CsvImport = CsvImport::find($id);
      if ( is_null($CsvImport) ) {
        return "No import with this ID found!";
      }
      $type = $CsvImport->table_type;
      $toDelete = $CsvImport->$type;
      foreach ($toDelete as $delete) {
          $delete->delete();
      }
      $CsvImport->delete();
      return "Import Data deleted!";
    }
}
