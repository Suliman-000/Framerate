<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('requires authentication', function () {
    delete(route('comment.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can delete a comment', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route('comment.destroy', $comment));

    $this->assertModelMissing($comment);
});

it('redirects to the post show page', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comment.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post_id));
});

it('redirects to the post show page with the page query parameter', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->delete(route('comment.destroy', ['comment' => $comment, 'page' => 2]))
        ->assertRedirect(route('posts.show', ['post' => $comment->post_id, 'page' => 2]));
});

it('prevent deleting a comment you didnt create', function () {
    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->delete(route('comment.destroy', $comment))
        ->assertForbidden();
});

it('prevent deleting a comment posted a hour ago', function () {
    $this->freezeTime();

    $comment = Comment::factory()->create();

    $this->travel(1)->hour();

    actingAs($comment->user)
        ->delete(route('comment.destroy', $comment))
        ->assertForbidden();
});
