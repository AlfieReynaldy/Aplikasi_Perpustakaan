@extends('components.user.sidebar')

@section('main')
    <div class="row">
        {{-- <div class="col-2">
            @include('components.user.sidebar')
        </div> --}}
        {{-- <div class="col-10"> --}}
        <div class="col">
            @foreach ($pemberitahuans as $pemberitahuan)
                <div class="alert alert-primary" role="alert">
                    {{ $pemberitahuan->isi }}
                </div>
            @endforeach
            <div class="row">
                @foreach ($bukus as $buku)
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header">
                                <img src="{{ asset($buku->foto) }}" style="height: 150px;object-fit: cover;" class="card-img"
                                    alt="....">
                            </div>
                            <div class="card-body">
                                <h4 style="font-size: 24px; font-weight: bold">
                                    {{ $buku->judul }}
                                </h4>
                                <span class="badge bg-secondary">{{ $buku->kategori->nama }}
                                </span>

                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-start">
                                            {{ $buku->pengarang }}
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-end">{{ $buku->penerbit->nama }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <form method="POST" action="{{ route('user.form_peminjaman_dashboard') }}">
                                    @csrf
                                    <input type="hidden" value="{{ $buku->id }}" name="buku_id">
                                    <button type="submit" class="btn btn-primary">Pinjam</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- </div> --}}
    </div>
    {{-- @include('user.components.sidebar')
    @foreach ($pemberitahuan as $p)
        <div class="alert alert-info">
            {{ $p->isi }}
        </div>
    @endforeach
    <div class="row">
    
        @foreach ($buku as $b)
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        {{ $b->kategori->nama }}
                </div>
                <div class="card-body">
                    {{ $b->judul }}
                    {{ $b->pengarang }}
                    {{ $b->penerbit->nama }}
                </div>
                <div class="card-footer">
                    <a href="" class="btn btn-primary">Pinjem</a>
                </div>
            </div>
        @endforeach
    </div> --}}
@endsection