<?php

namespace App\Http\Controllers;

use App\Models\Employees;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $limit = $request->get("limit") * 1;
        $q = $request->get("q");
        $filter = $request->get('filter');

        $empty = Employees::all();
        $employee = Employees::paginate($limit ? $limit : 10)->withQueryString();
        if ($empty) {
            return response()->json($employee, 200);
        }
        if ($q) {
            $employee = Employees::query()
                ->where('name', 'like', '%' . $q . '%')
                ->paginate($limit);
        }
        if ($filter) {
            $employee = Employees::query()
                ->where(implode('', array_keys($filter)), 'like', '%' . implode("", array_values($filter)) . '%')
                ->paginate($limit);
        }
        else return response()->json("", 204);
    }

    public function getOne($id)
    {
        $employee = Employees::find($id);
        $supervisor = DB::table('employees')->where('id', $employee->supervisor)->get();
        if (!$supervisor->isEmpty()) {
            $employee->supervisor = $supervisor[0];
        } else {
            $employee->supervisor = (object)[];
        };
        if ($employee) {
            return response()->json(["message" => "Successfully Retrived Employee Details", "data" => $employee], 200);
        }
        return response()->json(["message" => "No Employee Found With The Given ID"], 400);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|string|unique:employees',
            'gender' => 'string|required',
            'role' => 'string|required',
            'phone_no' => 'string|required',
            'status' => 'boolean|required',
        ]);
        try {
            Employees::create($request->all());
            return response()->json(['message' => 'Employee Created Succesfully'], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 400);
        }
    }

    public function update($id, Request $request)
    {
        $employee = Employees::find($id);
        if ($employee) {
            $employee->update($request->all());
            return response()->json(["message" => "Employee Details Updated Successfully"], 200);
        }
        return response()->json(["message" => "No Employee Found With The Given ID"], 200);
    }

    public function destroy($id)
    {
        $employee = Employees::find($id);
        if ($employee) {
            $employee->delete();
            return response()->json(['message' => 'Employee Deleted Successfully'], 200);
        }
        return response()->json(['message' => 'No Employee Found With The Given ID'], 400);
    }
}
