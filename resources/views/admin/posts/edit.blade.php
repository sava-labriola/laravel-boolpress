@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <h1 class="mt-3 mb-3">Modifica post</h1>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.posts.update', ['post' => $post->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="titolo">Titolo</label>
                        <input type="text" name="title" class="form-control" id="titolo" placeholder="Titolo post" value="{{ old('title', $post->title) }}">
                    </div>
                    <div class="form-group">
                        <label for="testo">Testo articolo</label>
                        <textarea type="text" name="content" class="form-control" id="testo" placeholder="Scrivi qualcosa...">{{ old('content', $post->content) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoria:</label>
                        <select id="categoria" class="form-control" name="category_id">
                            <option value="">Seleziona categoria</option>
                            @foreach ($categories as $category)
                                <option
                                    {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}
                                    value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Salva</button>
                </form>
            </div>
        </div>
    </div>
@endsection
