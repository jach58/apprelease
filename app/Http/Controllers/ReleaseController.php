<?php

namespace App\Http\Controllers;

use App\Release;
use App\ReleaseDetails;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;

class ReleaseController extends Controller
{
    public function index(Request $request)
    {
        $releases = Release::all();
        return ['releases' => $releases];
    }

    public function store(Request $request)
    {
        $messages = [
            'nombre.required' => 'Es necesario ingresar un nombre para la liberación.',
            'nombre.min' => 'Es necesario ingresar minimo un caracter.',
            'nombre.max' => 'Es necesario ingresar menos de 100 caracteres.',
            'descripcion.required' => 'Es necesario ingresar una descripción para la liberación.',
            'descripcion.min' => 'Es necesario ingresar minimo un caracter.',
        ];

        $rules = [
            'nombre'=> 'required|min:1|max:100',
            'descripcion'=> 'required|min:1',
            'file' => 'required|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors(), 'code' => 422]);
        }


        $release = new Release();
        $release->nombre = $request->nombre;
        $release->descripcion = $request->descripcion;
        $release->status = 'nueva';
        $release->save();

        $file = $request->file('file');
        $path = public_path() . '/files/archivos';
        $fileName = uniqid() . $file->getClientOriginalName();
        $moved = $file->move($path, $fileName);


        if($moved){
            $releaseDetail = new ReleaseDetails();
            $releaseDetail->archivo = $fileName;
            $releaseDetail->release_id = $release->id;
            $releaseDetail->save();
        }

        return response()->json(['release' => $release,'relDel'=> $releaseDetail ,'message' => 'La liberación se ha enviado con éxito']);
    }
}
