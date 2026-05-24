<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assistant;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Room;
use App\Models\StudyProgram;
use Illuminate\Http\JsonResponse;

class MasterDataController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'study_programs' => StudyProgram::all(),
            'courses'        => Course::all(),
            'rooms'          => Room::all(),
            'lecturers'      => Lecturer::all(),
            'assistants'     => Assistant::all(),
        ]);
    }
}
