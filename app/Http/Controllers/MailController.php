<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;
use App\Models\Staff;
use App\Models\Hotel;
use App\Http\Controllers\StaffController;

class MailController extends Controller
{
    public function sendMail($password,$username){
        $results=Staff::all()->toArray();
        $hotels=Hotel::all()->toArray();
        Mail::to('fake@mail.com')->send(new Email($password,$username));
        
        return view('staff.staff-home', compact('results','hotels'))->with('message', 'Staff has been added successfully');
    }
}
