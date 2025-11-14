<?php

namespace App\Http\Controllers;

use App\Models\Category;
// Gunakan Form Request untuk validasi yang bersih (sesuai spek)
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- Kita perlu ini untuk membuat 'slug'

class CategoryController extends Controller
{
    /**
     * -----------------------------------------------------------------
     * 🔒 KEAMANAN: Terapkan Middleware di sini
     * -----------------------------------------------------------------
     * Ini memastikan hanya user yang 'auth' (login) DAN punya 'role:admin'
     * yang bisa mengakses SEMUA fungsi di controller ini.
     */
    public function __construct()
    {
        // Anda perlu membuat middleware 'role' ini
        // Sesuai spek: "Gunakan middleware & policy untuk pembatasan akses"
        // $this->middleware(['auth', 'role:admin']);
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Gunakan with('parent') untuk Eager Loading relasi parent
        // Ini akan mengambil SEMUA data parent hanya dalam 1 query tambahan.
        $categories = Category::with('parent')
                                ->orderBy('name', 'asc')
                                ->get();

        return view("categories.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kita perlu mengirim daftar kategori untuk dropdown 'Parent Category'
        $parentCategories = Category::all();

        // Asumsi view Anda ada di: resources/views/categories/create.blade.php
        return view("categories.create", compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // Ganti 'Request' dengan 'StoreCategoryRequest' yang spesifik
    public function store(StoreCategoryRequest $request)
    {
        // Ambil data yang SUDAH divalidasi dari FormRequest
        $validated = $request->validated();

        // Buat slug secara otomatis dari nama (sesuai spek)
        $validated['slug'] = Str::slug($validated['name']);

        // Simpan ke database
        Category::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('categories.index')->with('success', 'Kategori baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     * (Fungsi 'show' jarang dipakai di admin panel, tapi kita lengkapi)
     */
    // Gunakan Route Model Binding (lebih bersih dari 'string $id')
    public function show(Category $category)
    {
        // $category sudah otomatis di-FindOrFail oleh Laravel
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // Kita juga perlu daftar kategori untuk dropdown 'parent'
        // $parentCategories = Category::all();
        $parentCategories = Category::where('id', '!=', $category->id)->get();

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Ambil data yang SUDAH divalidasi
        $validated = $request->validated();

        // Update slug jika nama berubah
        $validated['slug'] = Str::slug($validated['name']);

        // Update data di database
        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Hapus kategori
        // Jika Anda sudah setup SoftDeletes di Model, ini akan jadi soft delete
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
