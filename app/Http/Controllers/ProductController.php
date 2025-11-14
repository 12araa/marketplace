<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // <-- Perlu ini untuk dropdown
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- Perlu ini untuk Slug
// Impor Form Request yang akan Anda buat
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * -----------------------------------------------------------------
     * 🔒 KEAMANAN: Menerapkan Policy (Sesuai Spek)
     * -----------------------------------------------------------------
     * Ini adalah cara yang lebih canggih dari middleware.
     * 'authorizeResource' secara otomatis menghubungkan controller ini
     * dengan 'ProductPolicy' yang akan kita buat nanti.
     * * Ini akan otomatis mengecek:
     * - index() -> policy 'viewAny'
     * - create() -> policy 'create'
     * - store() -> policy 'create'
     * - edit() -> policy 'update'
     * - update() -> policy 'update'
     * - destroy() -> policy 'delete'
     */
    public function __construct()
    {
        // 'product' adalah nama parameter di route: products/{product}
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Mulai query kosong
        $query = Product::query();

        // 2. ===== INI DIA PERBAIKANNYA =====
        //    Cek apakah user yang login BUKAN admin
        if (auth()->user()->role !== 'admin') {

            // Jika BUKAN admin (berarti dia vendor),
            // tambahkan filter where: HANYA ambil produk
            // yang 'vendor_id'-nya SAMA DENGAN ID user yang login
            $query->where('vendor_id', auth()->id());
        }

        // 3. Ambil data SETELAH difilter,
        //    lengkapi dengan relasi (Eager Loading) dan Paginasi
        $products = $query->with(['category', 'vendor'])
                          ->latest()
                          ->paginate(20);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kita perlu data kategori untuk dropdown di form
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        // --- Logika Bisnis Tambahan ---
        // 1. Ambil vendor_id dari user yang sedang login
        $validated['vendor_id'] = auth()->id();

        // 2. Buat slug otomatis
        $validated['slug'] = Str::slug($validated['name']);

        // 3. Handle Upload Foto (Sesuai Spek)
        if ($request->hasFile('image')) {
            // Simpan file di 'storage/app/public/products'
            // 'public' di sini berarti visibility-nya public
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path; // Simpan path-nya ke db
        }
        // --- Selesai ---

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * (Umumnya tidak dipakai di panel admin, tapi baik untuk ada)
     */
    public function show(Product $product)
    {
        // 'authorizeResource' sudah otomatis mengecek policy 'view'
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // 'authorizeResource' sudah otomatis mengecek policy 'update'

        // Kita juga perlu kategori untuk dropdown
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // 'authorizeResource' sudah otomatis mengecek policy 'update'

        $validated = $request->validated();

        // Buat ulang slug jika nama produk berubah
        if ($request->name != $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle update foto (jika ada foto baru)
        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            // Storage::disk('public')->delete($product->image_path);

            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // 'authorizeResource' sudah otomatis mengecek policy 'delete'

        // Hapus file foto dari storage saat produk dihapus

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
