<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RentalRequest;

class RentalController extends Controller
{
    protected $rentalData;

    public function __construct()
    {
        //get file content and assign to a variable
        $this->rentalData = file_get_contents(base_path('database/store/rentals.json'));
    }


    public function createRental(RentalRequest $request)
    {
        //convert json object to an array, to carry out array operations on it
        $rentals = $this->convertJsonToArray();
        $validatedRentalData = $request->validated();

        $validatedRentalData = [
            'id' => count($rentals) + 1,
            'movie_id' => $request->movie_id,
            'date' => $request->date,
            'vendor' => $request->vendor,
        ];

        array_push($rentals, $validatedRentalData); // add new element to the array
        //convert array back to json string and store new json object in file
        file_put_contents(
            base_path('database/store/rentals.json'),
            json_encode($rentals, JSON_PRETTY_PRINT)
        );

        return response()->json([
            'rental' => $validatedRentalData,
            'message' => 'Rental Created Successfully'
        ]);
    }


    public function showRentals()
    {
        //read the json file content and convert to an array
        $rentals = $this->convertJsonToArray();
        return response()->json([
            'message' => 'Rentals Retrieved Successfully',
            'rentals' => $rentals
        ]);
    }


    public function singleRental(Request $request)
    {
        $rentalId = $request->id;
        //convert json data to an array
        $rentals = $this->convertJsonToArray();
        //loop through the array to confirm if id exist
        foreach ($rentals as $rental) {
            if ($rental['id'] == $rentalId) {
                return response()->json([
                    'message' => 'Rental Retrieved Successfully',
                    'rental' => $rental
                ]);
            }
        }
        return response()->json('Rental with id ' . $rentalId . ' not found');
    }


    public function updateRental(Request $request)
    {
        $rentalId = $request->id;
        // convert json file content to an array
        $rentals = $this->convertJsonToArray();
        //loop through the array, check if id exist and update array data
        foreach ($rentals as $i => $rental) {
            if ($rental['id'] == $rentalId) {

                $rentals[$i]['movie_id'] = is_null($request->movie_id)
                    ? $rentals[$i]['movie_id']
                    : $request->movie_id;
                $rentals[$i]['date'] = is_null($request->date)
                    ? $rentals[$i]['date']
                    : $request->date;
                $rentals[$i]['vendor'] = is_null($request->vendor)
                    ? $rentals[$i]['vendor']
                    : $request->vendor;

                //convert array back to json string and store updated data
                file_put_contents(
                    base_path('database/store/rentals.json'),
                    json_encode($rentals, JSON_PRETTY_PRINT)
                );

                return response()->json([
                    'message' => 'Rental Updated Succesfully',
                    'rental' => $rentals[$i]
                ]);
            }
        }
        return response()->json('Movie with id ' . $rentalId . ' not found');
    }


    public function deleteRental(Request $request)
    {
        $rental_id = $request->id;
        $rentals = $this->convertJsonToArray();

        //loop through the array and check if the id exist
        foreach ($rentals as $i => $rental) {
            if ($rental['id'] == $rental_id) {

                array_splice($rentals, $i, 1); // delete element from the array

                //convert array back to json string and save
                file_put_contents(
                    base_path('database/store/rentals.json'),
                    json_encode($rentals, JSON_PRETTY_PRINT)
                );

                return response()->json([
                    'message' => 'Rental Deleted Successfully',
                    'rentals' => $rentals
                ]);
            }
        }

        return response()->json('Rental with id ' . $rental_id . ' not found');
    }


    public function convertJsonToArray()
    {
        //convert json object to an array, to carry out array operations on it
        return json_decode($this->rentalData, true);
    }
}