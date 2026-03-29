<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidates: Testimonials, Gallery Images, Newsletter, Carousel, and Contact Messages.
     */
    public function up(): void
    {
        // 1. Testimonials
        if (!Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('position')->nullable(); // Correspondendo ao modelo
                $table->text('testimonial'); // Correspondendo ao modelo
                $table->string('photo')->nullable(); // Correspondendo ao modelo
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 2. Gallery Images
        if (!Schema::hasTable('gallery_images')) {
            Schema::create('gallery_images', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('description')->nullable();
                $table->string('image_path');
                $table->string('image_url')->nullable();
                $table->string('category')->default('general');
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 3. Newsletter
        if (!Schema::hasTable('newsletter_subscribers')) {
            Schema::create('newsletter_subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('name')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Carousel Slides
        if (!Schema::hasTable('carousel_slides')) {
            Schema::create('carousel_slides', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('image'); // Note: model uses 'image' not 'image_path'
                $table->string('logo_path')->nullable();
                $table->string('logo_position')->nullable(); // Consolidated patch
                $table->integer('logo_scale')->default(100); // Consolidated patch
                $table->string('alt_text')->nullable();
                $table->string('link')->nullable(); // Correspondendo ao fillable
                $table->string('link_text')->nullable();
                $table->string('text_position')->default('center');
                $table->string('text_alignment')->default('center');
                $table->integer('overlay_opacity')->default(50);
                $table->string('overlay_color')->default('#000000');
                $table->string('text_color')->default('#ffffff');
                $table->string('button_style')->default('primary');
                $table->string('transition_type')->default('fade');
                $table->integer('transition_duration')->default(700);
                $table->boolean('show_indicators')->default(true);
                $table->boolean('show_controls')->default(true);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Contact Messages
        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('subject')->nullable();
                $table->text('message');
                $table->string('status')->default('unread'); // unread, read, replied
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('carousel_slides');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('gallery_images');
        Schema::dropIfExists('testimonials');
    }
};
