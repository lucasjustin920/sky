<?php

namespace LaraZeus\Sky\Http\Livewire;

use LaraZeus\Sky\Models\Post;
use LaraZeus\Sky\Models\Tag;
use Livewire\Component;

class Posts extends Component
{
    public function render()
    {
        return view('zeus-sky::themes.'.config('zeus-sky.theme').'.home')
            ->with([
                'posts' => Post::NotSticky()->orderBy('published_at', 'desc')->get(),
                'pages' => Post::page()->orderBy('published_at', 'desc')->whereNull('parent_id')->get(),
                'tags' => Tag::withCount('posts')->where('type', 'category')->get(),
                'stickies' => Post::sticky()->get(),
                'recent' => Post::take(5)->get(),
            ])
            ->layout('zeus-sky::themes.'.config('zeus-sky.theme').'.layouts.app');
    }
}
