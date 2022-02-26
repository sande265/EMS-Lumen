<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Profile()
    {
        return response()->json(["message" => "Successfully Retrived User Details", "data" => auth()->user()], 200);
    }

    public function getAllUsers(Request $request)
    {
        $limit = $request->get("limit") * 1;
        $q = $request->get("q");
        $filter = $request->get('filter');

        $users = User::paginate($limit)->withQueryString();
        if ($q) {
            $users = User::query()
                ->where('name', 'like', '%' . $q . '%')
                ->paginate($limit);
        }
        if ($filter) {
            $users = User::query()
                ->where(implode('', array_keys($filter)), 'like', '%' . implode("", array_values($filter)) . '%')
                ->paginate($limit);
        }
        return response()->json($users, 200);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json(["message" => "Successfully Retrived User", "data" => $user], 200);
        }
        return response()->json(["message" => "No User Found With The Given ID"], 400);
    }

    public function updateUser($id, Request $request)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($request->only(['name', 'email', 'role', 'status']));
            return response()->json(["message" => "User Details Updated Successfully"], 200);
        }
        return response()->json(["message" => "No User Found With The Given ID"], 200);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User Deleted Successfully'], 200);
        }
        return response()->json(['message' => 'No User Found With The Given ID'], 400);
    }
}
