<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Staff;
use App\Models\User;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;

class StaffController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $results = Staff::select('id', 'name', 'gender', 'birthdate', 'phone', 'email', 'address', 'role', 'username', 'hotel_id')
            ->orderBy('id', 'desc')
            ->get();
        $hotels = Hotel::select('id', 'name')
            ->orderBy('name')
            ->get();
        return view('staff.staff-home',compact('results','hotels'));
    }

    public function page2()
    {
        $hotels = Hotel::select('id', 'name')
            ->orderBy('name')
            ->get();
        return view('staff.staff-add', compact('hotels'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'birthdate'=>'required',
            'gender'=>'required',
            'phone'=>'required|regex:/^\+?[1-9]\d(?:[\s-]?\d){6,13}$/',
            'email' => 'required|email|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/|unique:staff,email',
            'address'=>'required',
            'role'=>'required',
            'username'=>'',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Staff member could not be added. Try again.');
        }

        $data = $validator->validated();

                $staff=new Staff();
                $staff->name = $data['name'];
                $staff->birthdate = $data['birthdate'];
                $staff->gender = $data['gender'];
                $staff->phone = $data['phone'];
                $staff->email = $data['email'];
                $staff->address = $data['address'];
                $staff->role = $data['role'];

              if ($staff->role == 'Receptionist') {
                    $username = $this->generateUsernameFromName($staff->name);
                    $passwords = $this->generateRandomString(12);

                    $user = new User();
                    $user->name = $staff->name;
                    $user->email =  $staff->email;
                    $user->username = $username;
                    $user->password = Hash::make($passwords);
                    $user->save();
                    $staff->username = $username;
                    $staff-> hotel_id = $request->hotel_id;
                    $staff->save();

                    Mail::to('fake@mail.com')->send(new Email($passwords, $username));

                    return $this->respondSuccess($request, 'New staff added successfully', route('staff'));
                }
               else{
                $staff->username = null;
                $staff-> hotel_id = $request->hotel_id;
                $staff->save();
                
                return $this->respondSuccess($request, 'New staff added successfully', route('staff'));
               }
            }

    function generateRandomString($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    private function generateUsernameFromName(string $name): string
    {
        $base = Str::of($name)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '')
            ->value();

        if ($base === '') {
            $base = 'staff';
        }

        $base = substr($base, 0, 24);
        $candidate = $base;
        $suffix = 1;

        while ($this->usernameExists($candidate)) {
            $suffix++;
            $suffixText = (string) $suffix;
            $candidate = substr($base, 0, max(1, 24 - strlen($suffixText))) . $suffixText;
        }

        return $candidate;
    }

    private function usernameExists(string $username): bool
    {
        return User::where('username', $username)->exists()
            || Staff::where('username', $username)->exists();
    }

    
public function edit(Request $request){
    $validator = Validator::make($request->all(), [
     'id'=> 'required|exists:staff,id',
     'name'=> 'required',
     'gender'=> 'required',
     'birthdate'=> 'required',
     'phone'=> 'required|regex:/^\+?[1-9]\d(?:[\s-]?\d){6,13}$/',
     'email'=> 'required|email|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/|unique:staff,email,' . $request->id,
     'hotel_id'=> 'required|exists:hotels,id',
    ]);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Staff member could not be edited. Try again.');
    }

    $data = $validator->validated();

    $update=[
     'id'=> $data['id'],
     'name'=> $data['name'],
     'gender'=>$data['gender'],
     'birthdate'=>$data['birthdate'],
     'phone'=>$data['phone'],
     'email'=>$data['email'],
     'hotel_id'=>$data['hotel_id'],
    ];

    DB::table('staff')->where('id',$data['id'])->update($update);             
    return $this->respondSuccess($request, 'Staff has been edited successfully', '/staff');
}


    public function destroy(Request $request){
        if ($request->role == 'Receptionist') {
            $staff = DB::table('staff')->where('id', $request->id)->first();
                DB::table('users')->where('username', $staff->username)->delete();
                DB::table('staff')->where('id', $request->id)->delete();
                return $this->respondSuccess($request, 'Staff deleted successfully', '/staff');
            }
        else{
            DB::table('staff')->where('id',$request->id)->delete();
            return $this->respondSuccess($request, 'Staff deleted successfully', '/staff');
        }
       
    }

}
