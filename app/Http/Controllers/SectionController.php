<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        return Section::all()->values();
    }

    public function store(Request $request)
    {
        $fields = $request->validate(
            [
                'name' => 'required|string',
            ]
        );

        $created = Section::create(
            [
                'name' => $fields['name'],
            ]
        );

        if($created) {
            return response()->json(
                [
                    'message' => 'Section created successfully',
                    'section' => $created,
                ],
                200
            );
        }
    }

    public function update(Request $request, string $id)
    {
        $section = Section::where('id', $id)->get()->first();
        if($section) {
            $updated = $section->update($request->all());
            if($updated) {
                return response()->json(
                    [
                        'message' => 'Section updated successfully',
                        'section' => $section,
                    ],
                    200
                );
            }
        }
    }

    public function destroy(string $id)
    {
        $section = Section::where('id', $id)->get()->first();
        if($section) {
            $deleted = $section->update(['is_deleted' => true]);
            if($deleted) {
                return response()->json(
                    [
                        'message' => 'Section deleted successfully',
                        'section' => $section,
                    ],
                    200
                );
            }
        }
    }
}
