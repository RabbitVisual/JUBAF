<?php

namespace App\Policies;

use App\Models\User;
use Modules\Blog\App\Models\BlogComment;

class BlogCommentPolicy
{
    public function viewAny(User $user): bool
    {
        return user_can_access_admin_panel($user) || user_can_access_diretoria_panel($user);
    }

    public function view(User $user, BlogComment $blogComment): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, BlogComment $blogComment): bool
    {
        return user_can_manage_blog($user);
    }

    public function delete(User $user, BlogComment $blogComment): bool
    {
        return user_can_manage_blog($user);
    }
}
