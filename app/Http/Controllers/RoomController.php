<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::all()->values();
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'room_name' => 'required|string',
        ]);

        $created = Room::create([
            'room_name' => $fields['room_name'],
        ]);

        if ($created) {
            return response()->json([
                'message' => 'Room created successfully',
                'room' => $created,
            ], 200);
        }
    }

    public function update(Request $request, string $id)
    {
        $room = Room::where('id', $id)->get()->first();
        if ($room) {
            $updated = $room->update($request->all());
            if ($updated) {
                return response()->json([
                    'message' => 'Room updated successfully',
                    'room' => $room,
                ], 200);
            }
        }
    }

    public function destroy(string $id)
    {
        $room = Room::where('id', $id)->get()->first();
        if ($room) {
            $deleted = $room->update([
                'is_deleted' => true
            ]);
            if ($deleted) {
                return response()->json([
                    'message' => 'Room deleted successfully',
                ], 200);
            }
        }
    }

    public function borrowRoom(string $id) {
        $room = Room::where('id', $id)->get()->first();
        if($room) {
            $updated = $room->update([
                'borrower' => auth()->user()->id,
                'borrow_time' => Carbon::now()->toDateTimeLocalString(),
                'status' => 'occupied'
            ]);
            if($updated) {
                return response()->json([
                    'message' => 'Room borrowed successfully',
                    'room' => $room,
                ], 200);
            }
        }
    }

    public function returnRoom(string $id) {
        $room = Room::where('id', $id)->get()->first();
        if($room) {
            $updated = $room->update([
                'return_time' => Carbon::now()->toDateTimeLocalString(),
                'status' => 'available'
            ]);
            Activity::create([
                'source' => $room->room_name . '',
                    'data'=> json_encode($room)
                ]);

            $room->update([
                'borrower' => null,
                'borrow_time' => null,
                'return_time' => null,
            ]);
            if($updated) {
                return response()->json([
                    'message' => 'Room borrowed successfully',
                    'room' => $room,
                ], 200);
            }
        }
    }
}
