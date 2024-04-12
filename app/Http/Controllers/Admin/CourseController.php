<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = \App\Models\Course::all();
        return view('admin.courses.index', compact('courses'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        \App\Models\Course::create($validatedData);
        return redirect()->route('admin.courses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = \App\Models\Course::findOrFail($id);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = \App\Models\Course::findOrFail($id);
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        $course = \App\Models\Course::findOrFail($id);
        $course->update($validatedData);
        return redirect()->route('admin.courses.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = \App\Models\Course::findOrFail($id);
        $course->delete();
        return redirect()->route('admin.courses.index');
    }
}
