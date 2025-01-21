<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * Controllerのindexに対応している
     */
    public function viewAny(User $user): bool
    {
        return true; // 誰でもアクセス可能
    }

    /**
     * Determine whether the user can view the model.
     *
     * Controllerのshowに対応している
     */
    public function view(User $user, Post $post): bool
    {
        return true; // 誰でもアクセス可能
    }

    /**
     * Determine whether the user can create models.
     *
     * Controllerのstoreに対応している
     */
    public function create(User $user): bool
    {
        // controllerのstoreに対応している

        return Auth::check(); // 認証済みのユーザーのみアクセス可能
    }

    /**
     * Determine whether the user can update the model.
     *
     * Controllerのupdateに対応している
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Controllerのdestroyに対応している
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    // Determine whether the user can restore the model.
    // public function restore(User $user, Post $post): bool
    // {
    //     //
    // }

    // Determine whether the user can permanently delete the model.
    // public function forceDelete(User $user, Post $post): bool
    // {
    //     //
    // }
}
