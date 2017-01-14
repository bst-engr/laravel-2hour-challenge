<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Booking;
use App\Customer;
use App\City;
use App\Cleaner;

use Illuminate\Http\Request;
use Session, Validator, Auth, DB;

class ScheduleController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $city = City::all();
        $citylist = array();
        foreach ($city as $row) {
            $citylist[$row->id] = $row->city_name;
        }
        return view('schedule.index', compact('citylist'));
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
        $customer = Customer::where('phone_number','=', $requestData['phone_number'])->first();
        var_dump($customer);
        $messages = array();
        $customerValidator;
        //if record doesn't exists then insert a new record
        if(!$customer) {
            
            $customerValidator = Validator::make($requestData, Customer::$rules);
            if($customerValidator->passes()) {
                $customer = Customer::create($requestData);
                $requestData['customer_id'] = $customer->id;

            } 
        } else {
            //customer already signed up.
            $requestData['customer_id'] = $customer->id;
        }
        var_dump($requestData);

        //validate requesting data for booking.
        $requestData['cleaner_id'] = 1; //temporary assigning cleaning id 
        $Bookingvalidator = Validator::make($requestData, Booking::$rules);

        if($Bookingvalidator->passes()) {
            //check if any cleaner is available or not.
            
            $cleaner = $this->checkCleaner($requestData);
            if($cleaner) {
                $requestData['cleaner_id'] = $cleaner->id;
                Booking::create($requestData);
                Session::flash('success_flash_message', $cleaner->first_name.' '.$cleaner->last_name.' Assigned for your booking');
                return redirect('/');
            } else {

                Session::flash('fail_flash_message', 'we could not fulfill your request at this slot please select another date.');

                return redirect('/')
                ->withInput($requestData);        
            }
        } 
        
        if($customerValidator->fails() || $Bookingvalidator->fails() ) {

            $errors = $customerValidator->messages()->merge($Bookingvalidator->messages());
            //unsetting temporary value before return back.
            unset($requestData['cleaner_id']);
            // redirect our user back to the form with the errors from the validator
        
            return redirect('/')
                ->withInput($requestData)
                ->withErrors($errors);
        }
        
        
    }

    /**
     * [checkCleaner description]
     * @param  [type] $requestData [description]
     * @return [type]              [description]
     */
    public function checkCleaner($requestData) {
        $nonAvailable = Booking::where('date','=',$requestData['date'])->pluck('cleaner_id')->toArray();

        return Cleaner::whereNotIn('id', $nonAvailable)->first();

    }

}
