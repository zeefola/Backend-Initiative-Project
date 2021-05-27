<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    protected $userData;

    public function __construct()
    {
        //get file content and assign to a variable
        $this->userData = file_get_contents(base_path('database/store/users.json'));
    }


    public function createUser(UserRequest $request)
    {
        //convert json object to an array, to carry out array operations on it
        $users = $this->convertJsonToArray();
        $validatedUserData = $request->validated();

        $validatedUserData = [
            'id' => count($users) + 1,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        array_push($users, $validatedUserData); // add new element to the array
        //convert array back to json string and store new json object in file
        file_put_contents(
            base_path('database/store/users.json'),
            json_encode($users, JSON_PRETTY_PRINT)
        );

        return response()->json([
            'user' => $validatedUserData,
            'message' => 'User Created Successfully'
        ]);
    }


    public function showUsers()
    {
        //read the json file content and convert to an array
        $users = $this->convertJsonToArray();
        return response()->json([
            'message' => 'Users Retrieved Successfully',
            'users' => $users
        ]);
    }


    public function singleUser(Request $request)
    {
        $userId = $request->id;
        //convert json data to an array
        $users = $this->convertJsonToArray();

        //loop through the array and check if id exist
        foreach ($users as $user) {
            if ($user['id'] == $userId) {
                return response()->json([
                    'message' => 'User Retrieved Successfully',
                    'user' => $user
                ]);
            }
        }

        return response()->json('User with id ' . $userId . ' not found');
    }


    public function updateUser(Request $request)
    {
        $userId = $request->id;
        // convert json file content to an array
        $users = $this->convertJsonToArray();

        //loop through the array, check if id exist and update array data
        foreach ($users as $i => $user) {
            if ($user['id'] == $userId) {

                $users[$i]['name'] = is_null($request->name)
                    ? $users[$i]['name']
                    : $request->name;
                $users[$i]['email'] = is_null($request->email)
                    ? $users[$i]['email']
                    : $request->email;
                $users[$i]['password'] = is_null($request->password)
                    ? $users[$i]['password']
                    : $request->password;

                //convert array back to json string and store updated data
                file_put_contents(
                    base_path('database/store/users.json'),
                    json_encode($users, JSON_PRETTY_PRINT)
                );

                return response()->json([
                    'message' => 'User Updated Succesfully',
                    'user' => $users[$i]
                ]);
            }
        }

        return response()->json('User with id ' . $userId . ' not found');
    }


    public function deleteUser(Request $request)
    {
        $user_id = $request->id;
        $users = $this->convertJsonToArray();

        //loop through the array and check if the id exist
        foreach ($users as $i => $user) {
            if ($user['id'] == $user_id) {

                array_splice($users, $i, 1); // delete element from the array

                //convert array back to json string and save
                file_put_contents(
                    base_path('database/store/users.json'),
                    json_encode($users, JSON_PRETTY_PRINT)
                );

                return response()->json([
                    'message' => 'User Deleted Successfully',
                    'users' => $users
                ]);
            }
        }

        return response()->json('User with id ' . $user_id . ' not found');
    }


    public function convertJsonToArray()
    {  //convert json object to an array, to carry out array operations on it
        return json_decode($this->userData, true);
    }
}