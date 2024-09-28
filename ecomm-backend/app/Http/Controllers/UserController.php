<?php
namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use illuminate\Support\Facades\Hash;
use App\Models\User;
class UserController extends Controller
{
    function register1(Request $req){
        //$request->file;exit;
        $path = $req->file('image')->store('public/images');
        $user = new User;
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = $req->password;
        $user->image = $path;
        //$user->image = 'image.png';
        $user->save();
        return $user;
    }

    function items(){
        $users = User::all()->makeVisible('password');
        //dd($users);// Assuming you're fetching all users from a model
        return response()->json($users);
    }

    function register(Request $req)
{
    // Validate the incoming request
    $validator = Validator::make($req->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password' => 'required|string|max:8',
    ]);

    // If validation fails, return the errors
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Store the uploaded photo in the 'public/users' directory
    $photoPath = $req->file('photo')->store('users', 'public');

    // Optionally get the public URL for the photo
    $photoUrl = asset('storage/' . $photoPath);

    // Return the response with the name and photo URL

    $user = new User;
    $user->name = $req->name;
    $user->email = $req->email;
    $user->password = $req->password;
    $user->image = $photoUrl;
    //$user->image = 'image.png';
    $user->save();

    // return response()->json([
    //     'name' => $req->name,
    //     'photo_url' => $photoUrl,
    //     'message' => 'User registered successfully'
    // ], 201);
    $users = User::all();
    return response()->json($users);
}

// Delete item
public function destroy($id)
{
    //echo "here";exit;
    $item = User::find($id);

    if (!$item) {
        return response()->json(['message' => 'Item not found'], 404);
    }

    $item->delete();

    return response()->json(['message' => 'Item deleted successfully'], 200);
}

// Update User
public function update(Request $req, $id)
{
   // Validate the incoming request
   $validator = Validator::make($req->all(), [
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255',
    'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    'password' => 'required|string|max:8',
]);

// If validation fails, return the errors
if ($validator->fails()) {
    return response()->json(['errors' => $validator->errors()], 422);
}

// Store the uploaded photo in the 'public/users' directory
$photoPath = $req->file('photo')->store('users', 'public');

// Optionally get the public URL for the photo
$photoUrl = asset('storage/' . $photoPath);


    


    // Find the user by ID
    $user = User::find($id);
    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $user->name = $req->name;
    $user->email = $req->email;
    $user->password = $req->password;
    $user->image = $photoUrl;
    //$user->image = 'image.png';
    $user->save();

    $users = User::all();
    return response()->json($users);
}




}
