<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Booking;

class HotelBookingController extends Controller
{
    public function create($id)
    {
        $hotel = Hotel::findOrFail($id);
        return view('page.hotel.booking', compact('hotel'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'quantity' => 'required|integer|min=1',
        ]);

        Booking::create([
            'hotel_id' => $id,
            'name' => $request->name,
            'phone' => $request->phone,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'quantity' => $request->quantity,
        ]);

        return redirect()->back()->with('success', 'Đặt phòng thành công!');
    }
}
