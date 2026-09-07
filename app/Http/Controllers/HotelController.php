<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use DB;

class HotelController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $hotels=Hotel::all()->toArray();

        return view('hotel.hotel-home',  compact('hotels'));
    }


    public function page2(){

        return view('hotel.hotel-add');
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|regex:/^\+?[1-9]\d(?:[\s-]?\d){6,13}$/',
            'email' => 'required|email|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
            'year' => 'required',
            'image' => 'required',
        ]);

        $name = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->storeAs('/public/images/', $name);

        auth()->user()->hotel()->create([
            'name' => $data['name'],
            'code' => $this->generateHotelCode($data['name']),
            'address' => $data['address'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'year' => $data['year'],
            'image' => $name,
        ]);

        return $this->respondSuccess($request, 'Hotel has been added successfully', '/hotel');
    }

    public function edit(Request $request){
        $data = $request->validate([
            'id' => 'required|exists:hotels,id',
            'name' => 'required',
            'address' => 'required',
            'year' => 'required',
            'phone' => 'required|regex:/^\+?[1-9]\d(?:[\s-]?\d){6,13}$/',
            'email' => 'required|email|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $name = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('/public/images/', $name);
            $update = [
                'id' => $data['id'],
                'name' => $data['name'],
                'address' => $data['address'],
                'year' => $data['year'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'image' => $name,
            ];
        } else {
            $update = [
                'id' => $data['id'],
                'name' => $data['name'],
                'address' => $data['address'],
                'year' => $data['year'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ];
        }

        $currentCode = DB::table('hotels')->where('id', $data['id'])->value('code');
        if (empty($currentCode)) {
            $update['code'] = $this->generateHotelCode($data['name'], (int) $data['id']);
        }

        DB::table('hotels')->where('id', $data['id'])->update($update);
        return $this->respondSuccess($request, 'Hotel has been edited successfully', '/hotel');
    }

    public function destroy(Request $request){
        DB::table('hotels')->where('id',$request->id)->delete();
        return $this->respondSuccess($request, 'Hotel deleted successfully', '/hotel');
    }

    private function generateHotelCode(string $hotelName, ?int $ignoreHotelId = null): string
    {
        $letters = preg_replace('/[^A-Za-z]/', '', strtoupper($hotelName));
        if (empty($letters)) {
            $letters = 'H';
        }

        $usedCodesQuery = Hotel::query();
        if (!is_null($ignoreHotelId)) {
            $usedCodesQuery->where('id', '!=', $ignoreHotelId);
        }

        $usedCodes = $usedCodesQuery
            ->whereNotNull('code')
            ->pluck('code')
            ->map(fn ($code) => strtoupper((string) $code))
            ->all();

        for ($i = 0; $i < strlen($letters); $i++) {
            $candidate = $letters[$i];
            if (!in_array($candidate, $usedCodes, true)) {
                return $candidate;
            }
        }

        for ($i = 0; $i < 26; $i++) {
            $candidate = chr(ord('A') + $i);
            if (!in_array($candidate, $usedCodes, true)) {
                return $candidate;
            }
        }

        return 'H' . strtoupper(substr(md5($hotelName . microtime()), 0, 2));
    }
}
