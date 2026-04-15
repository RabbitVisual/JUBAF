<?php

namespace App\Policies;

use App\Models\User;
use Modules\Blog\App\Models\BlogCategory;

class BlogCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return user_can_access_admin_panel($user) || user_can_access_diretoria_panel($user);
    }

    public function view(User $user, BlogCategory $blogCategory): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return user_can_manage_blog($user);
    }

    public function update(User $user, BlogCategory $blogCategory): bool
    {
        return user_can_manage_blog($user);
    }

    public function delete(User $user, BlogCategory $blogCategory): bool
    {
        return user_can_manage_blog($user);
    }
}
