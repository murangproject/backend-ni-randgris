<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Activity;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return Schedule::with('user')->get()->values();
    }

    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'day' => 'required|string',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'semester' => 'required|string',
                'section' => 'required|string',
                'subject' => 'required|string',
                'room' => 'required|string',
                'user_id' => 'required|exists:users,id',
            ]
        );

        $created = Schedule::create(
            [
                'day' => $fields['day'],
                'start_time' => $fields['start_time'],
                'end_time' => $fields['end_time'],
                'semester' => $fields['semester'],
                'section' => $fields['section'],
                'subject' => $fields['subject'],
                'room' => $fields['room'],
                'user_id' => $fields['user_id'],
            ]
        );

        if($created) {
            return response()->json(
                [
                    'message' => 'Schedule created successfully',
                    'schedule' => $created,
                ],
                200
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = Schedule::where('id', $id)->get()->first();
        if($schedule) {
            $updated = $schedule->update($request->all());
            if($updated) {
                return response()->json(
                    [
                        'message' => 'Schedule updated successfully',
                        'schedule' => $schedule,
                    ],
                    200
                );
            }
        }
    }

    public function updateStatus(Request $request, string $id)
    {
        $schedule = Schedule::where('id', $id)->get()->first();
        if($schedule) {
            $updated = $schedule->update($request->all());
            if($updated) {
                Activity::create(
                    [
                        'type'=> 'attendance',
                        'source' => $schedule->id,
                        'data' => json_encode($schedule),
                    ]
                );
                return response()->json(
                    [
                        'message' => 'Schedule updated successfully',
                        'schedule' => $schedule,
                    ],
                    200
                );
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::where('id', $id)->get()->first();
        if($schedule) {
            $deleted = $schedule->update(['is_deleted' => true]);
            if($deleted) {
                return response()->json(
                    [
                        'message' => 'Schedule deleted successfully',
                        'schedule' => $schedule,
                    ],
                    200
                );
            }
        }
    }

    public function getScheduleByUserId(string $id)
    {
        return Schedule::where('user_id', $id)->get()->values();
    }
}
