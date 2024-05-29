<?php

namespace App\Http\Controllers;

use App\Models\MailBox;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\StoreMailBoxRequest;
use App\Http\Requests\UpdateMailBoxRequest;

use Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\MailBoxResource;
use Illuminate\Support\Facades\Auth;


class MailBoxController extends Controller
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

        $mail_boxes = MailBox::all();
        if ($request->ajax()) {
            // $mail_boxes = MailBox::all();
            return DataTables::of(MailBoxResource::collection($mail_boxes))->make(true);
        }

        return view('mail_boxes', compact('mail_boxes'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMailBoxRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $mailBox = MailBox::create($validated);

        $mail_boxes = new MailBoxResource($mailBox);
        return view('mail_boxes', compact('mail_boxes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MailBox $mailBox)
    {
        return response()->json(new MailBoxResource($mailBox));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MailBox $mailBox)
    {
        return response()->json(new MailBoxResource($mailBox));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMailBoxRequest $request, MailBox $mailBox)
    {
        $mailBox->update($request->validated());
        return redirect()->route('mail_boxes.index')->with('success', 'Mail updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailBox $mailBox)
    {
        // dd($mailBox);
        if ($mailBox) {
            $mailBox->delete();
            return  redirect()->route('mail_boxes.index')->with('success', 'Mail deleted successfully');
        } else {
            return redirect()->route('mail_boxes.index')->with('error', 'Mail not found.');
        }
    }

    public function change_status(MailBox $mailBox)
    {
        // dd($mailBox->status );
        if($mailBox->status==1){
            $mailBox->status=0;
            $message= trans('Revert Successfully');
        }else{
            $mailBox->status=1;
            $message= trans('Send Successfully');
        }
        $mailBox->save();
        return response()->json($message);

    }
}
