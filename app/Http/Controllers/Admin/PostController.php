<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin/posts/index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();

        return view('admin/posts/create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData = $request->all();

        $this->validation($formData);

        $newPost = new Post();

        $newPost->fill($formData);

        $newPost->slug = Str::slug($newPost->title, '-');

        $newPost->save();

        return redirect()->route('admin.posts.show', $newPost);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin/posts/show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $types = Type::all();

        return view('admin/posts/edit', compact(['post', 'types']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $formData = $request->all();

        $this->validation($formData);

        $post->slug = Str::slug($formData['title'], '-');

        $post->update($formData);

        return redirect()->route('admin.posts.index', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index');
    }

    private function validation($formData) {
        $validator = Validator::make($formData, [
            'title' => 'required|max:100|min:5',
            'content' => 'required|min:10',
            'type_id' => 'nullable|exists:types,id',
        ], [
            'title.required' => 'Inserisci un titolo!',
            'title.max' => 'Il titolo deve avere massimo :max caratteri!',
            'title.min' => 'Il titolo deve avere minimo :min caratteri!',
            'content.required' => 'Inserisci il contenuto del post!',
            'content.min' => 'Il contenuto del post deve avere minimo :min caratteri!',
            'type_id.exists' => 'La categoria non è presente'
        ])->validate();
    }
}
