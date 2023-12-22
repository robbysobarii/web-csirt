@foreach ($galleries as $gallery)
    <div class="carousel-item-galery mx-4 " style="max-height:300px "> <!-- Atur lebar item menjadi 33.333% -->
        <a href="{{ route('galeri', ['id' => $gallery->id]) }}" style="display: block;max-height:500px;" >
            <img src="{{ asset('storage/' . $gallery->gambar) }}" class="d-block" alt="{{ $gallery->judul }}">
            <div class="bgGalery">
                <p class="text-white">{{ $gallery->caption }}</p>
            </div>
        </a>
    </div>
@endforeach