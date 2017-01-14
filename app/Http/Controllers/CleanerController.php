<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cleaner;
use App\Booking;
use App\City;
use Illuminate\Http\Request;
use Session, Validator, Auth;

class CleanerController extends Controller
{
    /**
     * [__construct description]
     */
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cleaner = Cleaner::paginate(25);

        return view('cleaner.index', compact('cleaner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $city = City::all();
        return view('cleaner.create', compact('city'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        $requestData['city_list'] = implode(",", $requestData['city_list']);

        $validator = Validator::make($requestData, Cleaner::$rules);
        if($validator->passes()) {
            Cleaner::create($requestData);

            Session::flash('flash_message', 'Cleaner added!');

            return redirect('cleaner');
        } else{
            // get the error messages from the validator
            $messages = $validator->messages();
            $requestData['city_list'] = explode(",", $requestData['city_list']);
            // redirect our user back to the form with the errors from the validator
            return redirect('cleaner/create')
                ->withInput($requestData)
                ->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cleaner = Cleaner::findOrFail($id);
        $booking = Booking::where('cleaner_id','=',$id)->get();
        return view('cleaner.show', compact('cleaner', 'booking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cleaner = Cleaner::findOrFail($id);
        $cleaner['city_list'] = explode(",", $cleaner['city_list']);
        $city = City::all();
        return view('cleaner.edit', compact('cleaner', 'city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $requestData = $request->all();
        var_dump($requestData);
        $requestData['city_list'] = implode(",", $requestData['city_list']);

        $validator = Validator::make($requestData, Cleaner::$rules);
        
        if($validator->passes()) {
            $cleaner = Cleaner::findOrFail($id);
            $cleaner->update($requestData);

            Session::flash('flash_message', 'Cleaner updated!');

            return redirect('cleaner');
        } else{
            // get the error messages from the validator
            $messages = $validator->messages();
            $requestData['city_list'] = explode(",", $requestData['city_list']);
            // redirect our user back to the form with the errors from the validator
            return redirect('cleaner/'.$id.'/edit')
                ->withInput($requestData)
                ->withErrors($validator);
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Cleaner::destroy($id);

        Session::flash('flash_message', 'Cleaner deleted!');

        return redirect('cleaner');
    }
}
