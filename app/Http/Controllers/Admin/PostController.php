<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use App\Category;
use Illuminate\Support\Str;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with('category')->get();
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // validazione dei dati
        $request->validate([
            'title' => 'required|max:255|unique:posts,title',
            'content' => 'required'
        ]);

        $dati = $request->all();
        // genero lo slug a partire dal titolo
        $slug = Str::of($dati['title'])->slug('-');
        $slug_originale = $slug;
        // verifico che lo slug sia unico
        $post_trovato = Post::where('slug', $slug)->first();
        $contatore = 0;
        while($post_trovato) {
            $contatore++;
            // genero un nuovo slug concatenando un contatore
            $slug = $slug_originale . '-' . $contatore;
            $post_trovato = Post::where('slug', $slug)->first();
        }
        // arrivati a questo punto sono sicura che $slug contiene uno slug unico
        $dati['slug'] = $slug;
        // salvo i dati del post
        $nuovo_post = new Post();
        $nuovo_post->fill($dati);
        $nuovo_post->save();
        return redirect()->route('admin.posts.index');
    }

    public function show($id)
    {
        $post = Post::find($id);
        if($post) {
            return view('admin.posts.show', compact('post'));
        } else {
            return abort('404');
        }
    }

    public function edit($id)
    {
        $post = Post::find($id);
        if($post) {
            $categories = Category::all();
            $data = [
                'post' => $post,
                'categories' => $categories
            ];
            return view('admin.posts.edit', $data);
        } else {
            return abort('404');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255|unique:posts,title,'.$id,
            'content' => 'required'
        ]);

        $dati = $request->all();
        // genero lo slug a partire dal titolo
        $slug = Str::of($dati['title'])->slug('-');
        $slug_originale = $slug;
        // verifico che lo slug sia unico
        $post_trovato = Post::where('slug', $slug)->first();
        $contatore = 0;
        while($post_trovato) {
            $contatore++;
            // genero un nuovo slug concatenando un contatore
            $slug = $slug_originale . '-' . $contatore;
            $post_trovato = Post::where('slug', $slug)->first();
        }
        // arrivati a questo punto sono sicura che $slug contiene uno slug unico
        $dati['slug'] = $slug;

        $post = Post::find($id);
        $post->update($dati);

        return redirect()->route('admin.posts.index');
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if($post) {
            $post->delete();
            return redirect()->route('admin.posts.index');
        } else {
            return abort('404');
        }
    }
}
