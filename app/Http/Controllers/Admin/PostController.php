<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Technology;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $technologies = Technology::all();

        return view('admin/posts/create', compact('types', 'technologies'));
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

        if($request->hasFile('post_image')) {
            $path = Storage::put('post_images', $request->post_image);
            $formData['post_image'] = $path;
        }

        $newPost = new Post();

        $newPost->fill($formData);

        $newPost->slug = Str::slug($newPost->title, '-');

        $newPost->save();

        if(array_key_exists('technologies', $formData)) {
            $newPost->technologies()->attach($formData['technologies']);
        }

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
        $technologies = Technology::all();

        return view('admin/posts/edit', compact(['post', 'types', 'technologies']));
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

        if($request->hasFile('post_image')) {
            if($post->post_image) {
                Storage::delete($post->post_image);
            }
            $path = Storage::put('post_images', $request->post_image);
            $formData['post_image'] = $path;

        }

        $post->slug = Str::slug($formData['title'], '-');

        $post->update($formData);

        if(array_key_exists('technologies', $formData)) {
            $post->technologies()->sync($formData['technologies']);

        } else {
            $post->technologies()->detach();
            
        }

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
        if($post->post_image) {
            Storage::delete($post->post_image);
        }
        
        $post->delete();

        return redirect()->route('admin.posts.index');
    }

    private function validation($formData) {
        $validator = Validator::make($formData, [
            'title' => 'required|max:100|min:5',
            'content' => 'required|min:10',
            'type_id' => 'nullable|exists:types,id',
            'technologies' => 'exists:technologies,id',
            'post_image' => 'nullable|image|max:4096'
        ], [
            'title.required' => 'Inserisci un titolo!',
            'title.max' => 'Il titolo deve avere massimo :max caratteri!',
            'title.min' => 'Il titolo deve avere minimo :min caratteri!',
            'content.required' => 'Inserisci il contenuto del post!',
            'content.min' => 'Il contenuto del post deve avere minimo :min caratteri!',
            'type_id.exists' => 'La categoria non è presente',
            'technologies' => 'Questa tecnologia non è presente nel nostro sito',
            'post_image.max' => 'La dimensione del file è troppo grande',
            'post_image.image' => 'Il file deve essere un immagine'

        ])->validate();
    }
}
