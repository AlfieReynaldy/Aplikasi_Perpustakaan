@extends('layouts.app')

@section('content')
    <div class="container">
        @include('components.user.sidebar')
        @if (session('status'))
            <div class="alert alert-{{ session('status') }}" role="alert">
                {{ session('message') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h4>Update Profil</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('user.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <table class="table">
                        <tr>
                            <th>Foto</th>
                            <td>
                              <input type="file" name="foto" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>
                                <input type="text" class="form-control" name="fullname"
                                    value="{{ Auth::user()->fullname }}">
                            </td>
                        </tr>
                        <tr>
                            <th>NIS</th>
                            <td>
                                <input type="text" class="form-control" name="nis" value="{{ Auth::user()->nis }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>
                                <textarea name="alamat" class="form-control" id="" cols="30" rows="10">{{ Auth::user()->alamat }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>
                                <input type="text" class="form-control" name="username"
                                    value="{{ Auth::user()->username }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Password</th>
                            <td>
                                <input type="password" placeholder="Sandi Belum di Ubah" class="form-control"
                                    name="password">
                            </td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>
                                <input type="text" class="form-control" name="kelas" value="{{ Auth::user()->kelas }}">
                            </td>
                        </tr>
                    </table>
                    <div class="text-end">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection