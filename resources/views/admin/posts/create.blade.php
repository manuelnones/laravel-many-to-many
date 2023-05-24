@extends('layouts/admin')

@section('content')
    <main class="container">
        <form action="{{route('admin.posts.store')}}" method="POST">
            @csrf

            <div class="text-center mt-4">
                <h1>Aggiungi un post</h1>
            </div>

            <div class="mt-5">
                <div class="mb-4">
                    <label for="title">Titolo</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{old('title')}}">
                    @error('title')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type_id">Categoria</label>
                    <select name="type_id" id="type_id" class="form-select @error('type_id') is-invalid @enderror">
                        <option>Nessuna</option>
                        @foreach ($types as $type)
                            <option value="{{$type->id}}" {{$type->id == old('type_id') ? 'selected' : ''}}>{{$type->name}}</option>
                         @endforeach
                    </select>
                    @error('type_id')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror   
                </div>

                <div class="mb-4">
                    <label for="content">Contenuto</label>
                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" cols="30" rows="10">{{old('content')}}</textarea>
                    @error('content')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="btn-container d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Aggiungi</button>
            </div>

        </form>
    </main>
@endsection