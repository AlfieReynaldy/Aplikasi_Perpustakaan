@extends('layouts.app')
@section('content')
<div class="container">
    @include('components.user.sidebar')

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h2>Buku yang Sudah Dikembalikan</h2>
                </div>
            </div>

            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Kondisi Buku</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjaman as $key => $p)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $p->buku->judul }}</td>
                            <td>{{ $p->tanggal_pengembalian }}</td>
                            <td>{{ $p->kondisi_buku_saat_dikembalikan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
