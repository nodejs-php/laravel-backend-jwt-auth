<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FileController extends Controller
{
    public function index(): Collection|array
    {
        $files = File::with('project')->get();

        return $files;
    }


    public function show($file): Model|Collection|Builder|array|null
    {
        $file = File::with('project')->find($file);

        return $file;
    }


    public function store(Request $request): Application|Response|JsonResponse|array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {

        try {
            $project_file = $request->file('file');

            // check if file is available;
            if ($project_file) {

                $projectFile = $project_file;
                $filename = $request->file_name;
                $fileExt = $projectFile->getClientOriginalExtension();
                $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf', 'docx', 'doc'];
                $destinationPath = public_path('/assets/projects/');

                if (!in_array($fileExt, $allowedExtensions)) return response(['status' => 500, 'message' => "Kindly upload a valid file format"], 500);

                $filename = $filename . '.' . $fileExt;
                $projectFile->move($destinationPath, $filename);
            }

            $file_url = "/assets/projects/";


            $file = File::create([
                "project_id" => $request->project_id,
                "file_name" => $request->file_name,
                "file" => $project_file ? $file_url . $filename : null,
            ]);

            return ([
                $file,
                "message" => "File Uploaded"
            ]);


        } catch (\Throwable $th) {
            $response = [
                "status" => 500,
                "message" => "Something went wrong",
                "error" => $th->getMessage()
            ];

            return response()->json($response, 500);
        }
    }


    public function update(Request $request, $file)
    {

        $project_file = $request->file('file');

        // check if file is available;
        if ($project_file) {

            $projectFile = $project_file;
            $filename = "project-" . time();
            $fileExt = $projectFile->getClientOriginalExtension();
            $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'pdf', 'docx', 'doc'];
            $destinationPath = public_path('/assets/projects/');

            if (!in_array($fileExt, $allowedExtensions)) return response(['status' => 500, 'message' => "Kindly upload a valid file format"], 500);

            $filename = $filename . '.' . $fileExt;
            $projectFile->move($destinationPath, $filename);
        }

        $file_url = "/assets/projects/";


        $file = File::find($file);

        $file->update([
            "file_name" => $request->file_name,
            "file" => $project_file ? $file_url . $filename : null,
        ]);

        return $file;
    }


}
