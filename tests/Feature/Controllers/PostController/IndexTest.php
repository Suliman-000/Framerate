<?php

use App\Models\Post;
use function Pest\Laravel\get;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use Inertia\Testing\AssertableInertia;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->component('Posts/Index', true)
    );
});

it('passes posts to the view', function () {

    $posts = Post::factory(3)->create();

    get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});
