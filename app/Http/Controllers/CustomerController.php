<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Booking;
use DB;

class CustomerController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index()
    {
        $results = Customer::select('id', 'name', 'personal_id', 'birthdate', 'phone', 'gender')
            ->orderBy('id', 'desc')
            ->get();

        return view('customers.customers-home',compact('results'));
    }

    public function userIndex()
    {
        $results = Customer::select('id', 'name', 'personal_id', 'birthdate', 'phone', 'gender')
            ->orderBy('id', 'desc')
            ->get();

        return view('user.customers',compact('results'));
    }


    public function page2(){
        return view('customers.customers-add');
    }

    
    public function store(Request $request)
    {
        $data=$request->validate([
            'personal_id'=>'required|regex:/^[A-Z][0-9]{8}[A-Z]$/',
            'name'=>'required',
            'birthdate'=>'required|date',
            'phone'=>'required|regex:/^\+?[1-9]\d(?:[\s-]?\d){6,13}$/',
            'gender'=>'required',
                ]);

                $customer=new Customer();
                $customer->name = $data['name'];
                $customer->personal_id = $data['personal_id'];
                $customer->birthdate = $data['birthdate'];
                $customer->phone = $data['phone'];
                $customer->gender = $data['gender'];
                $customer->save();
                
            return $this->respondSuccess($request, 'Customer has been added successfully', '/customer');
    }

    public function edit(Request $request){
           $data = $request->validate([
            'id'=> 'required|exists:customers,id',
            'name'=> 'required',
            'personal_id'=> 'required|regex:/^[A-Z][0-9]{8}[A-Z]$/',
            'birthdate'=> 'required|date',
            'phone'=> 'required|regex:/^\+?[1-9]\d(?:[\s-]?\d){6,13}$/',
            'gender'=> 'required',
           ]);

           $update=[
            'id'=> $data['id'],
            'name'=>$data['name'],
            'personal_id'=>$data['personal_id'],
            'birthdate'=>$data['birthdate'],
            'phone'=>$data['phone'],
            'gender'=>$data['gender'],
           ];

           DB::table('customers')->where('id',$data['id'])->update($update);             
            return $this->respondSuccess($request, 'Customer has been edited successfully', '/customer');
    }


    public function destroy(Request $request){
        $customerId = $request->id;
    
        $bookingCount = DB::table('bookings')->where('customer_id', $customerId)->count();
    
        if ($bookingCount > 0) {
            // If the customer has bookings, do not delete and return a message
            return $this->respondError($request, 'Customer has bookings and cannot be deleted', 409, '/customer');
        } else {
            // If the customer has no bookings, delete the customer record
            DB::table('customers')->where('id', $customerId)->delete();
            return $this->respondSuccess($request, 'Customer deleted successfully', '/customer');
        }
    }
    
}
