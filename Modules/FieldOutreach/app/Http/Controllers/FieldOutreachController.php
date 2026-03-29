<?php

namespace Modules\FieldOutreach\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldOutreachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('fieldoutreach::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fieldoutreach::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('fieldoutreach::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('fieldoutreach::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
