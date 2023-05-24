@extends('layouts/admin')

@section('content')
    <table class="mt-5 table table-striped">
        <thead class="text-uppercase">
            <th>Titolo</th>
            <th>Contenuto</th>
            <th>Slug</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td>{{$post->title}}</td>
                <td>{{$post->content}}</td>
                <td>{{$post->slug}}</td>
                <td>
                    <a href="{{route('admin.posts.show', $post)}}"><i class="fa-solid fa-arrow-up-right-from-square mx-4"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="btn-container d-flex justify-content-center m-4">
        <button class="btn btn-primary"><a href="{{route('admin.posts.create')}}" class="text-light text-decoration-none">Aggiungi un post</a></button>
    </div>
@endsection