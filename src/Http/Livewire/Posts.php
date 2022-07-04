<?php

namespace LaraZeus\Sky\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use LaraZeus\Sky\Models\Post;
use LaraZeus\Sky\Models\Tag;
use Livewire\Component;

class Posts extends Component
{
    public function render()
    {
        $search = strtolower(request()->get('search'));

        $posts = Post::NotSticky();
        $posts = $this->applySearch($posts, $search);
        $posts = $posts
            ->orderBy('published_at', 'desc')
            ->get();

        $pages = Post::page();
        $pages = $this->applySearch($pages, $search);
        $pages = $pages
            ->orderBy('published_at', 'desc')
            ->whereNull('parent_id')
            ->get();

        $recent = Post::posts()
                    ->limit(config('zeus-sky.site_recent_count', 5))
                    ->orderBy('published_at', 'desc')
                    ->get();

        return view(app('theme').'.home')
            ->with([
                'posts'    => $posts,
                'pages'    => $pages,
                'recent'   => $recent,
                'tags'     => Tag::withCount('postsPublished')->where('type', 'category')->get(),
                'stickies' => Post::sticky()->get(),
            ])
            ->layout(config('zeus-sky.layout'));
    }

    private function applySearch(Builder $query, string $search): Builder
    {
        if ($search) {
            return $query->where(function ($query) use ($search) {
                foreach (['title', 'slug', 'content', 'description'] as $attribute) {
                    $query->orWhere(DB::raw("lower($attribute)"), 'like', "%$search%");
                }

                return $query;
            });
        }

        return $query;
    }
}
