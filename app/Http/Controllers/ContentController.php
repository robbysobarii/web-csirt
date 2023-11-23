<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Gallery;
use App\Models\Carousel;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::all();
        return view('administrator.contentManagement', compact('contents'));
    }

    public function getContents()
    {
        $content = Content::latest()->get();
        
        $galleries = Gallery::all();
        $carousels = Carousel::all();

        // Kirim data konten ke tampilan
        return view('user.beranda', compact('content', 'galleries','carousels'));
    }

    public function listContent()
    {
        $content = Content::latest()->get();

        // Kirim data konten ke tampilan
        return view('user.daftarBerita', compact('content'));
    }

    public function showNews($id)
    {
        $content = Content::find($id);
        if (!$content) {
            abort(404);
        }
        // Fetch the previous and next content
        $prevContent = Content::where('id', '<', $id)->orderBy('id', 'desc')->first();
        $nextContent = Content::where('id', '>', $id)->orderBy('id')->first();

        // Pass the data to the view
        return view('user.berita', compact('content', 'prevContent', 'nextContent'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isiKonten' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type' => 'required',
        ]);

        // Store the image in the storage directory
        $gambarPath = $request->file('gambar')->store('images', 'public');

        // Save the file path in the database
        Content::create([
            'judul' => $request->judul,
            'isi_konten' => $request->isiKonten,
            'gambar' => $gambarPath,
            'type' => $request->type,
        ]);

        return redirect()->route('admin.contentManagement');
    }
    public function delete($id)
    {

        $content = Content::find($id);

        if ($content) {
            $content->delete();
            return redirect()->route('admin.contentManagement')->with('success', 'Content deleted successfully');
        }

        return redirect()->route('admin.contentManagement')->with('error', 'Content not found');
    }

}