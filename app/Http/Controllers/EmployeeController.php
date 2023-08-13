<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index() {
		return view('index');
	}

	// handle fetch all eamployees ajax request
	public function fetchAll() {
		$emps = Employee::all();
		$output = '';
		if ($emps->count() > 0) {
			$output .= '<table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>
                        <span class="custom-checkbox">
                            <input type="checkbox" id="selectAll">
                            <label for="selectAll"></label>
                        </span>
                    </th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';
			foreach ($emps as $emp) {
                // Generate a unique identifier for each row
                $checkboxId = 'checkbox_' . $emp->id;
				$output .= '<tr>
                <td>' . $emp->id . '</td>
                <td>
                <span class="custom-checkbox">
                <input type="checkbox" id="' . $checkboxId . '" name="options[]" value="' . $emp->id . '">
                <label for="' . $checkboxId . '"></label>
                </span>
				</td>
                <td>' . $emp->name . '</td>
                <td>' . $emp->email . '</td>
                <td>' . $emp->address . '</td>
                <td>' . $emp->phone . '</td>
                <td>

                <a href="#editEmployeeModal" id="' . $emp->id . '" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
				<a href="#deleteEmployeeModal" id="' . $emp->id . '" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>

               </td>
              </tr>';
			}
			$output .= '</tbody></table>';
			echo $output;
		} else {
			echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
		}
	}

	// handle insert a new employee ajax request
	public function store(Request $request) {

		$empData = ['name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'address' => $request->address];
		Employee::create($empData);
		return response()->json([
			'status' => 200,
		]);
	}

    public function edit(Request $request) {
        $id = $request->id;
        $emp = Employee::find($id);
        return response()->json($emp);
    }

    public function update(Request $request) {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $emp = Employee::find($request->id);
        $empData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        $emp->update($empData);

        return response()->json([
            'status' => 200,
        ]);
    }

	// handle delete an employee ajax request
	public function delete(Request $request) {
		$id = $request->id;
		$emp = Employee::find($id);
        if (!$emp) {
            return response()->json(['error' => 'Employee not found'], 404);
        }
        Employee::destroy($id);

        // Return a success response
        return response()->json(['message' => 'Employee deleted successfully'], 200);

	}
}






