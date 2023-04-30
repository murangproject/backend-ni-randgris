<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        return Activity::all()->values();
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'type' => 'string',
            'source' => 'string',
            'data' => 'string',
        ]);

        $created = Activity::create([
            'type' => $fields['type'],
            'source' => $fields['source'],
            'data' => $fields['data'],
        ]);

        if($created) {
            return response()->json([
                'message' => 'Activity created successfully',
                'log' => $created,
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
