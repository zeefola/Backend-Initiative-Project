<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function addMovie(Request $request)
    {

        $file = file_get_contents(base_path('resources/lang/en/movie.json')); //read content of json file
        $data = json_decode($file, true); //json String to array, so you can perform array functions on the output
        //note that json object is different from an array

        $movie = [
            'id' => count($data["playlist"]) + 1,
            'title' => $request->input('title'),
            'release_date' => $request->input('release_date'),
            'genre' => $request->input('genre'),
            'producer' => $request->input('producer'),
            'synopsis' => $request->input('synopsis'),
        ];

        $data["playlist"] = array_values($data["playlist"]);



        array_push($data["playlist"], $movie); //add new item to array
        $newContent = json_encode($data); //converted array back to json string
        file_put_contents(base_path('resources/lang/en/movie.json'), $newContent); // override pervious content with new json string


        return response()->json($movie);
    }

    public function movies()
    {
        $file = file_get_contents(base_path('resources/lang/en/movie.json'));
        $data = json_decode($file, true);
        return response()->json($data);
    }

    // public function movie(Request $request){
    //     $file = file_get_contents(base_path('resources/lang/en/movie.json'));
    //     $data = json_decode($file, true);

    //     $id = $request->input('id');
    //     $index=$id-1;
    //     $result=$data["playlist"][$index];
    //     return $result;

    // }


    public function deleteMovie(Request $request)
    {

        //while deleting
        // you need to read the content again,
        // convert from json string back to array
        // search the array for the value using a loop or an array function.
        // delete the element
        // convert and save back
        // lobatan,so wa sure pe otitan 😌 ,im sha trying it out now...let me get to it

        $id = $request->input('id');
        $file = file_get_contents(base_path('resources/lang/en/movie.json'));
        $data = json_decode($file, true);


        $index = $id - 1;
        array_splice($data["playlist"], $index, 1);
        $newContent = json_encode($data); //converted array back to json string
        file_put_contents(base_path('resources/lang/en/movie.json'), $newContent);
        return response()->json($data);
    }

    public function updateMovie(Request $request)
    {
        //fech the json
        //convert it to an array
        //search for that particular array with the id
        //update the array
        //convert back to json and the json array

        $id = $request->input('id');
        $file = file_get_contents(base_path('resources/lang/en/movie.json'));
        $data = json_decode($file, true);
        $index = $id - 1;
        $data["playlist"][$index]['title'] = $request->input('title');
        $data["playlist"][$index]['release_date'] = $request->input('release_date');
        $data["playlist"][$index]['genre'] = $request->input('genre');
        $data["playlist"][$index]['producer'] = $request->input('producer');
        $data["playlist"][$index]['synopsis'] = $request->input('synopsis');
        $newContent = json_encode($data);
        file_put_contents(base_path('resources/lang/en/movie.json'), $newContent);
        return response()->json($data["playlist"][$index]);
    }
}