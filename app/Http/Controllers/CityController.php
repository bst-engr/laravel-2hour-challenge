<?php

namespace App\Http\Controllers;

use App\Http\Requests;

use App\City;
use Illuminate\Http\Request;
use Session, Validator, Auth;

class CityController extends Controller
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
        $city = City::paginate(25);

        return view('city.index', compact('city'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('city.create');
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
        
         $validator = Validator::make($requestData, City::$rules);
        
        if($validator->passes()) {
            City::create($requestData);

            Session::flash('flash_message', 'City added!');

            return redirect('city');
        } else{
            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('city/create')
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
        $city = City::findOrFail($id);

        return view('city.show', compact('city'));
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
        $city = City::findOrFail($id);

        return view('city.edit', compact('city'));
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
        
        $validator = Validator::make($requestData, City::$rules);
        
        if($validator->passes()) {
            $City = City::findOrFail($id);
            $City->update($requestData);

            Session::flash('flash_message', 'City updated!');

            return redirect('city');
        } else{
            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return redirect('city/'.$id.'/edit')
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
        City::destroy($id);

        Session::flash('flash_message', 'City deleted!');

        return redirect('city');
    }
}
