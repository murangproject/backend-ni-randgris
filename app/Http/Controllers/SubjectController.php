<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return Subject::all()->values();
    }

    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'code' => 'required|string',
                'name' => 'required|string',
            ]
        );

        $created = Subject::create(
            [
                'code' => $fields['code'],
                'name' => $fields['name'],
            ]
        );

        if($created) {
            return response()->json(
                [
                    'message' => 'Subject created successfully',
                    'subject' => $created,
                ],
                200
            );
        }
    }

    public function update(Request $request, string $id)
    {
        $subject = Subject::where('id', $id)->get()->first();
        if($subject) {
            $updated = $subject->update($request->all());
            if($updated) {
                return response()->json(
                    [
                        'message' => 'Subject updated successfully',
                        'subject' => $subject,
                    ],
                    200
                );
            }
        }
    }

    public function destroy(string $id)
    {
        $subject = Subject::where('id', $id)->get()->first();
        if($subject) {
            $deleted = $subject->update(['is_deleted' => true]);
            if($deleted) {
                return response()->json(
                    [
                        'message' => 'Subject deleted successfully',
                    ],
                    200
                );
            }
        }
    }
}
