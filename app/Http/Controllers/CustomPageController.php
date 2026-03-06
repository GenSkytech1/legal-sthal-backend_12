<?php

namespace App\Http\Controllers;

use App\Models\CustomPage;
use Illuminate\Http\Request;

class CustomPageController extends Controller
{
    public function index()
    {
        $pages = CustomPage::all();
        return response()->json([
            'status' => 'success',
            'data' => $pages
        ]);
    }

    public function show($id)
    {
        $page = CustomPage::find($id);
        if (!$page) {
            return response()->json(['status' => 'error', 'message' => 'Page not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $page
        ]);
    }

    public function findBySlug($slug)
    {
        $page = CustomPage::where('slug', $slug)->first();
        if (!$page) {
            return response()->json(['status' => 'error', 'message' => 'Page not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $page
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|unique:custom_pages,slug',
            'title' => 'required'
        ]);

        $data = $request->all();

        // Handle JSON fields
        $jsonFields = ['also_get', 'how_it_works', 'process_list', 'benefits', 'requirements', 'documents', 'what_you_get'];
        foreach ($jsonFields as $field) {
            if ($request->has($field) && is_string($request->input($field))) {
                $data[$field] = json_decode($request->input($field), true) ?? [];
            }
        }

        $page = CustomPage::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Page created successfully',
            'data' => $page
        ]);
    }

    public function update(Request $request, $id)
    {
        $page = CustomPage::find($id);
        if (!$page) {
            return response()->json(['status' => 'error', 'message' => 'Page not found'], 404);
        }

        $request->validate([
            'slug' => 'required|unique:custom_pages,slug,' . $id,
            'title' => 'required'
        ]);

        $data = $request->all();

        // Handle JSON fields
        $jsonFields = ['also_get', 'how_it_works', 'process_list', 'benefits', 'requirements', 'documents', 'what_you_get'];
        foreach ($jsonFields as $field) {
            if ($request->has($field) && is_string($request->input($field))) {
                $data[$field] = json_decode($request->input($field), true) ?? [];
            }
        }

        $page->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Page updated successfully',
            'data' => $page
        ]);
    }

    public function destroy($id)
    {
        $page = CustomPage::find($id);
        if (!$page) {
            return response()->json(['status' => 'error', 'message' => 'Page not found'], 404);
        }

        $page->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Page deleted successfully'
        ]);
    }
}
