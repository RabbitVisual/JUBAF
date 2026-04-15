<?php

namespace Modules\Blog\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Modules\Blog\App\Models\BlogPost;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Blog\App\Models\BlogTag;
use Modules\Blog\App\Models\BlogComment;

class BlogFullSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock mandatory routes for admin layout
        Route::get('/admin/dashboard', fn() => 'dashboard')->name('admin.dashboard');
    }

    /**
     * Create an admin user with proper role and permissions
     */
    private function createAdminUser(): User
    {
        $role = DB::table('roles')->where('name', 'super-admin')->first();
        if (!$role) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'super-admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $roleId = $role->id;
        }

        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin_' . uniqid() . '@jubaf.test',
            'password' => bcrypt('secret')
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => $roleId,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $user;
    }

    // =======================================
    // 1. Admin – Categories CRUD
    // =======================================

    #[Test]
    public function admin_can_manage_blog_categories()
    {
        $user = $this->createAdminUser();

        // Create
        $response = $this->actingAs($user)->post(route('admin.blog.categories.store'), [
            'name' => 'Notícias',
            'description' => 'Categoria de notícias',
            'is_active' => 1
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('blog_categories', ['name' => 'Notícias']);

        $category = BlogCategory::first();
        $this->assertNotEmpty($category->slug, 'Slug deve ser gerado automaticamente');

        // Update
        $this->actingAs($user)->put(route('admin.blog.categories.update', $category->id), [
            'name' => 'Eventos',
            'is_active' => 1
        ]);
        $this->assertEquals('Eventos', $category->fresh()->name);

        // Delete
        $this->actingAs($user)->delete(route('admin.blog.categories.destroy', $category->id));
        $this->assertDatabaseMissing('blog_categories', ['id' => $category->id]);
    }

    // =======================================
    // 3. Admin – Tags CRUD
    // =======================================

    #[Test]
    public function admin_can_manage_blog_tags()
    {
        $user = $this->createAdminUser();

        // Create
        $response = $this->actingAs($user)->post(route('admin.blog.tags.store'), [
            'name' => 'Urgente',
            'color' => '#ff0000'
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('blog_tags', ['name' => 'Urgente']);

        $tag = BlogTag::first();
        $this->assertNotEmpty($tag->slug);

        // Update
        $this->actingAs($user)->put(route('admin.blog.tags.update', $tag->id), [
            'name' => 'Importante'
        ]);
        $this->assertEquals('Importante', $tag->fresh()->name);

        // Delete
        $this->actingAs($user)->delete(route('admin.blog.tags.destroy', $tag->id));
        $this->assertDatabaseMissing('blog_tags', ['id' => $tag->id]);
    }

    // =======================================
    // 4. Admin – Posts CRUD
    // =======================================

    #[Test]
    public function admin_can_manage_blog_posts()
    {
        $user = $this->createAdminUser();
        $category = BlogCategory::create(['name' => 'Geral', 'is_active' => true]);
        $tag = BlogTag::create(['name' => 'Novidade']);
        // Create
        $data = [
            'title' => 'Meu Primeiro Post',
            'content' => 'Conteúdo do post de teste.',
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->toDateTimeString(),
            'tag_ids' => [$tag->id],
            'is_featured' => 0,
            'allow_comments' => 1
        ];

        $response = $this->actingAs($user)->post(route('admin.blog.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('blog_posts', ['title' => 'Meu Primeiro Post']);

        $post = BlogPost::first();
        $this->assertNotEmpty($post->slug);
        $this->assertCount(1, $post->tags);

        // Update
        $this->actingAs($user)->put(route('admin.blog.update', $post->id), [
            'title' => 'Post Atualizado',
            'content' => 'Novo conteúdo.',
            'category_id' => $category->id,
            'status' => 'draft',
            'is_featured' => 0,
            'allow_comments' => 1
        ]);
        $this->assertEquals('Post Atualizado', $post->fresh()->title);

        // Delete
        $this->actingAs($user)->delete(route('admin.blog.destroy', $post->id));
        $this->assertDatabaseMissing('blog_posts', ['id' => $post->id]);
    }

    // =======================================
    // 5. Public – Index and Show
    // =======================================

    #[Test]
    public function public_can_view_blog_index_and_posts()
    {
        $user = User::create(['name' => 'Author', 'email' => 'author_' . uniqid() . '@blog.com', 'password' => bcrypt('secret')]);
        $category = BlogCategory::create(['name' => 'Saúde', 'is_active' => true]);
        $post = BlogPost::create([
            'title' => 'Prevenção de Doenças',
            'content' => 'Conteúdo sobre saúde.',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'status' => 'published',
            'published_at' => now()->subDay()
        ]);

        $response = $this->get(route('blog.index'));
        $response->assertStatus(200);
        $response->assertSee('Prevenção de Doenças');

        $response = $this->get(route('blog.show', $post->slug));
        $response->assertStatus(200);
        $response->assertSee('Prevenção de Doenças');
    }

    // =======================================
    // 6. Public – Search
    // =======================================

    #[Test]
    public function public_can_search_posts()
    {
        $user = User::create(['name' => 'Author', 'email' => 'author2_' . uniqid() . '@blog.com', 'password' => bcrypt('secret')]);
        $category = BlogCategory::create(['name' => 'Geral', 'is_active' => true]);

        BlogPost::create([
            'title' => 'Vacinação 2026',
            'content' => 'Campanha de vacinação.',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'status' => 'published',
            'published_at' => now()
        ]);

        BlogPost::create([
            'title' => 'Outro Assunto',
            'content' => 'Nada demais.',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'status' => 'published',
            'published_at' => now()
        ]);

        $response = $this->get(route('blog.search', ['q' => 'Vacinação']));
        $response->assertStatus(200);
        $response->assertSee('Vacinação 2026');
        $response->assertDontSee('Outro Assunto');
    }

    // =======================================
    // 7. Public – Comments
    // =======================================

    #[Test]
    public function public_can_comment_on_posts()
    {
        $user = User::create(['name' => 'Author', 'email' => 'author3_' . uniqid() . '@blog.com', 'password' => bcrypt('secret')]);
        $category = BlogCategory::create(['name' => 'Geral', 'is_active' => true]);
        $post = BlogPost::create([
            'title' => 'Post comentado',
            'content' => 'Comente abaixo.',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'status' => 'published',
            'published_at' => now(),
            'allow_comments' => true
        ]);

        // storeComment requires authentication, sets status='approved' for logged-in users,
        // and only validates 'content' + 'parent_id' (author_name/email not accepted)
        $commenter = User::create(['name' => 'Commenter', 'email' => 'commenter_' . uniqid() . '@blog.com', 'password' => bcrypt('secret')]);

        $response = $this->actingAs($commenter)->post(route('blog.comments.store', $post->id), [
            'content' => 'Ótimo post!'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('blog_comments', [
            'post_id' => $post->id,
            'user_id' => $commenter->id,
            'status' => 'approved'
        ]);
    }

    // =======================================
    // 8. Admin – Comment Moderation
    // =======================================

    #[Test]
    public function admin_can_moderate_comments()
    {
        $user = $this->createAdminUser();
        $category = BlogCategory::create(['name' => 'Geral', 'is_active' => true]);
        $post = BlogPost::create([
            'title' => 'Post',
            'content' => '...',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'status' => 'published',
            'published_at' => now()
        ]);
        $comment = BlogComment::create([
            'post_id' => $post->id,
            'author_name' => 'Fake',
            'content' => 'Spam',
            'status' => 'pending'
        ]);

        // Approve
        $this->actingAs($user)->post(route('admin.blog.comments.approve', $comment->id));
        $this->assertEquals('approved', $comment->fresh()->status);

        // Reject
        $this->actingAs($user)->post(route('admin.blog.comments.reject', $comment->id));
        $this->assertEquals('rejected', $comment->fresh()->status);
    }

    // =======================================
    // 9. Public – RSS and Sitemap
    // =======================================

    #[Test]
    public function blog_rss_and_sitemap_are_accessible()
    {
        $response = $this->get(route('blog.rss'));
        $response->assertStatus(200);
        $this->assertStringContainsString('<rss', $response->getContent());

        $response = $this->get(route('blog.sitemap'));
        $response->assertStatus(200);
        $this->assertStringContainsString('<urlset', $response->getContent());
    }

    // =======================================
    // 10. Model – View Tracking
    // =======================================

    #[Test]
    public function blog_can_track_post_views()
    {
        $user = User::create(['name' => 'Author', 'email' => 'author4_' . uniqid() . '@blog.com', 'password' => bcrypt('secret')]);
        $category = BlogCategory::create(['name' => 'Geral', 'is_active' => true]);
        $post = BlogPost::create([
            'title' => 'Post Popular',
            'content' => 'Muito lido.',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'status' => 'published',
            'published_at' => now(),
            'views_count' => 0
        ]);

        $post->incrementViews('127.0.0.1', 'Mozilla', null);

        $this->assertEquals(1, $post->fresh()->views_count);
        $this->assertDatabaseHas('blog_views', ['post_id' => $post->id, 'ip_address' => '127.0.0.1']);
    }

    #[Test]
    public function api_lists_and_shows_published_posts(): void
    {
        $user = User::create(['name' => 'Author', 'email' => 'author_api_' . uniqid() . '@jubaf.test', 'password' => bcrypt('secret')]);
        $category = BlogCategory::create(['name' => 'Geral', 'is_active' => true]);
        $post = BlogPost::create([
            'title' => 'API Post Title',
            'slug' => 'api-post-title',
            'content' => 'Corpo.',
            'category_id' => $category->id,
            'author_id' => $user->id,
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        $index = $this->getJson(route('api.blog.v1.posts.index'));
        $index->assertOk();
        $index->assertJsonPath('data.0.title', 'API Post Title');

        $show = $this->getJson(route('api.blog.v1.posts.show', ['slug' => $post->slug]));
        $show->assertOk();
        $show->assertJsonPath('title', 'API Post Title');
    }
}
