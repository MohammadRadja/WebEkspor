<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class KontenController extends Controller
{
    /**
     * Tampilkan daftar konten
     */
    public function index()
    {
        try {
            $halaman   = Konten::where('jenis', 'halaman')->paginate(10);
            $artikel   = Konten::where('jenis', 'artikel')->paginate(10);
            $spanduk   = Konten::where('jenis', 'spanduk')->paginate(10);
            $komponen  = Konten::where('jenis', 'komponen')->paginate(10);

            return view('dashboard.penjual.konten', compact('halaman', 'artikel', 'spanduk', 'komponen'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data konten.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal memuat data konten.');
        }
    }

    /**
     * Simpan konten baru
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul'            => 'required|string|max:255',
                'jenis'            => 'required|in:halaman,artikel,spanduk,komponen',
                'konten'           => 'required|string',
                'kutipan'          => 'required|string|max:255',
                'gambar'           => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'tautan'           => 'nullable|string',
                'meta'             => 'nullable|array',
                'media'            => 'nullable|array',
                'diterbitkan_pada' => 'required|date',
                'status'           => 'nullable|in:terbit,draf',
                'penulis'          => 'nullable|string',
            ]);

            // Proses upload gambar
            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $file     = $request->file('gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $path     = public_path('uploads/konten');

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $file->move($path, $namaFile);
                $gambarPath = 'uploads/konten/' . $namaFile;
            }

            $konten = Konten::create([
                'judul'            => $request->judul,
                'slug'             => Str::slug($request->judul),
                'jenis'            => $request->jenis,
                'kutipan'          => $request->kutipan,
                'konten'           => $request->konten,
                'gambar'           => $gambarPath,
                'tautan'           => $request->tautan,
                'meta'             => $request->meta ?? [],
                'media'            => $request->media ?? [],
                'status'           => $request->status ?? 'draf',
                'diterbitkan_pada' => $request->diterbitkan_pada,
                'penulis'          => $request->penulis,
            ]);

            Log::info('Konten berhasil ditambahkan.', $konten->toArray());
            return redirect()->route('konten.index')->with('success', 'Konten berhasil ditambahkan.');
        } catch (ValidationException $e) {
            Log::warning('Validasi gagal.', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Store konten gagal.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menambahkan konten.');
        }
    }

    /**
     * Update konten
     */
    public function update(Request $request, Konten $konten)
    {
        try {
            $validated = $request->validate([
                'judul'            => 'required|string|max:255',
                'jenis'            => 'required|in:halaman,artikel,spanduk,komponen',
                'konten'           => 'required|string',
                'kutipan'          => 'required|string|max:255',
                'gambar'           => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'tautan'           => 'nullable|string',
                'meta'             => 'nullable|array',
                'media'            => 'nullable|array',
                'status'           => 'nullable|in:terbit,draf',
                'diterbitkan_pada' => 'required|date',
                'penulis'          => 'nullable|string',
            ]);

            $gambarPath = $konten->gambar;

            // Upload gambar baru jika ada
            if ($request->hasFile('gambar')) {
                // hapus file lama jika ada
                if ($konten->gambar && file_exists(public_path($konten->gambar))) {
                    unlink(public_path($konten->gambar));
                }

                $file     = $request->file('gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $path     = public_path('uploads/konten');

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $file->move($path, $namaFile);
                $gambarPath = 'uploads/konten/' . $namaFile;
            }

            $konten->update([
                'judul'            => $request->judul,
                'slug'             => Str::slug($request->judul),
                'jenis'            => $request->jenis,
                'kutipan'          => $request->kutipan,
                'konten'           => $request->konten,
                'gambar'           => $gambarPath,
                'tautan'           => $request->tautan,
                'meta'             => $request->meta ?? [],
                'media'            => $request->media ?? [],
                'status'           => $request->status ?? 'draf',
                'diterbitkan_pada' => $request->diterbitkan_pada,
                'penulis'          => $request->penulis,
            ]);

            return redirect()->route('konten.index')->with('success', 'Konten berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Update konten gagal.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal memperbarui konten.');
        }
    }

    /**
     * Hapus konten
     */
    public function destroy(Request $request, Konten $konten)
    {
        try {
            // hapus file gambar jika ada
            if ($konten->gambar && file_exists(public_path($konten->gambar))) {
                unlink(public_path($konten->gambar));
            }

            $konten->delete();

            if ($request->ajax()) {
                return response()->json(['message' => 'Konten berhasil dihapus.']);
            }

            return redirect()->route('konten.index')->with('success', 'Konten berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Hapus konten gagal.', ['error' => $e->getMessage()]);

            if ($request->ajax()) {
                return response()->json(['error' => 'Gagal menghapus konten.'], 500);
            }

            return redirect()->back()->with('error', 'Gagal menghapus konten.');
        }
    }
}