@extends('admin.layout')

@section('title', 'Tambah Kos')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Kos</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kos.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.kos.form')
    </form>
@endsection
