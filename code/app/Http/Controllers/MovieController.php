<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MovieRequest;

class MovieController extends Controller
{
    protected $movieData;

    public function __construct()
    {
        //read movies json file content
        $this->movieData = file_get_contents(base_path('database/store/movies.json'));
    }


    public function createMovie(MovieRequest $request)
    {
        //convert json object to an array, to carry out array operations on it
        $movies = $this->convertJsonToArray();
        $validatedMovieData = $request->validated();

        $validatedMovieData = [
            'id' => count($movies) + 1,
            'title' => $request->title,
            'release_year' => $request->release_year,
            'genre' => $request->genre,
            'overview' => $request->overview,
        ];

        array_push($movies, $validatedMovieData); // add new element to the array
        //convert array back to json string and store new json object in file
        file_put_contents(
            base_path('database/store/movies.json'),
            json_encode($movies, JSON_PRETTY_PRINT)
        );

        return response()->json([
            'movie' => $validatedMovieData,
            'message' => 'Movie Created Successfully'
        ]);
    }


    public function showMovies()
    {
        //read the json file content and convert to an array
        $movies = $this->convertJsonToArray();
        return response()->json([
            'message' => 'Movies Retrieved Successfully',
            'movies' => $movies
        ]);
    }


    public function singleMovie(Request $request)
    {
        $movieId = $request->id;
        //convert json data to an array
        $movies = $this->convertJsonToArray();

        //loop through the array and check if id exist
        foreach ($movies as $movie) {
            if ($movie['id'] == $movieId) {
                return response()->json([
                    'message' => 'Movie Retrieved Successfully',
                    'movie' => $movie
                ]);
            }
        }

        return response()->json('Movie with id ' . $movieId . ' not found');
    }


    public function updateMovie(Request $request)
    {
        $movieId = $request->id;
        // convert json file content to an array
        $movies = $this->convertJsonToArray();

        //loop through the array, check if id exist and update array data
        foreach ($movies as $i => $movie) {
            if ($movie['id'] == $movieId) {

                $movies[$i]['title'] = is_null($request->title)
                    ? $movies[$i]['title']
                    : $request->title;
                $movies[$i]['release_year'] = is_null($request->release_year)
                    ? $movies[$i]['release_year']
                    : $request->release_year;
                $movies[$i]['genre'] = is_null($request->genre)
                    ? $movies[$i]['genre']
                    : $request->genre;
                $movies[$i]['overview'] = is_null($request->overview)
                    ? $movies[$i]['overview']
                    : $request->overview;

                //convert array back to json string and store updated data
                file_put_contents(
                    base_path('database/store/movies.json'),
                    json_encode($movies, JSON_PRETTY_PRINT)
                );

                return response()->json([
                    'message' => 'Movie Updated Succesfully',
                    'movie' => $movies[$i]
                ]);
            }
        }

        return response()->json('Movie with id ' . $movieId . ' not found');
    }


    public function deleteMovie(Request $request)
    {
        $movie_id = $request->id;
        $movies = $this->convertJsonToArray();

        //loop through the array and check if the id exist
        foreach ($movies as $i => $movie) {
            if ($movie['id'] == $movie_id) {

                array_splice($movies, $i, 1); // delete element from the array

                //convert array back to json string and save
                file_put_contents(
                    base_path('database/store/movies.json'),
                    json_encode($movies, JSON_PRETTY_PRINT)
                );

                return response()->json([
                    'message' => 'Movie Deleted Successfully',
                    'movies' => $movies
                ]);
            }
        }

        return response()->json('Movie with id ' . $movie_id . ' not found');
    }

    public function convertJsonToArray()
    {
        //convert json object to an array, to carry out array operations on it
        return json_decode($this->movieData, true);
    }
}