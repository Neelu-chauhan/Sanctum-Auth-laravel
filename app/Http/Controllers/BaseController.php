<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
  public function SendResponse($results,$response){
    $response =[
        'success' => true,
        'message' => $results,
        'data' => $response
    ];
    return response()->json($response, 200);
  }
  public function SendError($error,$code=404,$errorMessage=[]){
    $response =[
        'success' => false,
        'message' => $error,
    ];
    if(!empty($errorMessage)){
        $response['data'] = $errorMessage;
    }
    return response()->json($response,$code);
  }
}
