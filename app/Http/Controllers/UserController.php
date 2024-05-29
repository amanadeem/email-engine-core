<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use App\Models\User;
use Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $users = User::all();
        if ($request->ajax()) {
            // $users = User::all();
            return DataTables::of(UserResource::collection($users))->make(true);
        }

        // return view('users');
        return view('users', compact('users'));

    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:255',
    //         'email' => 'required|email|unique:users|max:255',
    //         'phone' => 'required|min:8',
    //         'password' => 'required|min:8',
    //     ]);
    //     if ($validator->fails()) {
    //         return back()->with('error', $validator->errors()->first());
    //     }
    //     User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'password' => $request->password,
    //     ]);
    //     return redirect('/users');
    // }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        $user = new UserResource($user);
        return view('users', compact('user'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user = new UserResource($user);
        // return view('users', compact('user'));
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = new UserResource($user);
        // return view('users', compact('user'));
        return response()->json($user);
    }


    // public function update(Request $request)
    // {
    //     // Validate standard fields
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:255',
    //         'email' => ['required',Rule::unique('users')->ignore($request->user_id)],
    //         'phone' => 'required|min:8',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('users.index')->with('error', $validator->errors()->first());
    //     }
    //     $user = User::find($request->user_id);
    //     // dd($user,$request->all());
    //     if ($user) {
    //         $user->update([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'phone' => $request->phone,
    //         ]);
    //         $user->save();


    //         return redirect()->route('users.index')->with('success', 'User updated successfully.');
    //     } else {
    //         return redirect()->route('users.index')->with('error', 'User not found.');
    //     }
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        // $user = new UserResource($user);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        if ($user) {
            $user->delete();
            return  redirect()->route('users.index')->with('success', 'User deleted successfully');
        } else {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
    }

}
