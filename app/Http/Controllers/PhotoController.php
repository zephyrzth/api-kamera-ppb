<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Rules\Base64Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:256',
            'filename' => 'required|string|max:256',
            'photofile' => ['required', 'string', new Base64Image(8192)]
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => "error", 'message' => $validator->errors()], 401);
        }

        $filename = $request->input('filename');
        $photofile = $request->input('photofile');
        Storage::put('public/photos/' . $filename, base64_decode($photofile));
        // $request->file('photofile')->storeAs('public/photos', $filename);
        Photo::create([
            'name' => $request->input('name'),
            'filename' => $filename,
            'path' => 'storage/photos/' . $filename
        ]);
        return response()->json(['status' => "success", 'message' => "Foto tersimpan"], 201);
    }

    public function show(string $filename)
    {
        $data = Photo::where('filename', $filename)->first();
        return response()->json(['status' => "success", 'data' => $data], 200);
    }

    public function list()
    {
        $data = Photo::all();
        return response()->json(['status' => "success", 'data' => $data], 200);
    }
}
