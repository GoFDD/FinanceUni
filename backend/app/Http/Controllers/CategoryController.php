<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // GET /api/categories
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type'); // income ou expense

        $categories = Category::forUser($user->id)
            ->when($type, fn($q) => $q->where('type', $type))
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        $data['user_id'] = $user->id;

        $category = Category::create($data);

        return response()->json($category, 201);
    }

    // PUT /api/categories/{id}
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $category = Category::findOrFail($id);

        // só permite editar categorias do usuário (não editar sistema global)
        if ($category->user_id !== $user->id) {
            return response()->json(['message' => 'Ação não permitida'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        $category->update($data);

        return response()->json($category);
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        $user = Auth::user();

        $category = Category::findOrFail($id);

        if ($category->user_id !== $user->id) {
            return response()->json(['message' => 'Ação não permitida'], 403);
        }

        //  checar se existem transactions vinculadas e impedir exclusão
        // $count = $category->transactions()->count(); if ($count) ...

        $category->delete();

        return response()->json(['message' => 'Categoria excluída']);
    }
}
