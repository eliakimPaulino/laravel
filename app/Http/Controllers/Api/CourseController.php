<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function courseList()
    {
        $result = Course::select('name', 'thumbnail', 'lesson_num', 'price', 'id')->get();
        return response()->json([
            'code' => 200,
            'msg' => 'My course list is here',
            'data' => $result,
        ], 200);
        // return response()->json([
        //     'user' => Auth::user(),
        //     'message' => Auth::check() ? 'Usuário autenticado' : 'Usuário não autenticado'
        // ]);
    }
}
