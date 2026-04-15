@extends('blog::layouts.blog')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $canonicalPostUrl = route('blog.show', $post->slug);
    $postOgDescription = $post->og_description ?: Str::limit(strip_tags((string) ($post->excerpt ?: $post->content)), 160);
    $postOgImagePath = $post->og_image ?: $post->featured_image;
    $postOgImageMeta = $postOgImagePath
        ? (Str::startsWith($postOgImagePath, ['http://', 'https://']) ? $postOgImagePath : Storage::url($postOgImagePath))
        : \App\Support\SiteBranding::logoDefaultUrl();
    $jsonLdImageAbs = Str::startsWith((string) $postOgImageMeta, ['http://', 'https://']) ? $postOgImageMeta : url($postOgImageMeta);

    $jsonLdBlogPosting = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => $postOgDescription,
        'datePublished' => $post->published_at->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $canonicalPostUrl,
        ],
        'author' => [
            '@type' => 'Person',
            'name' => $post->author->name ?? 'JUBAF',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => \App\Support\SiteBranding::siteName(),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => \App\Support\SiteBranding::logoDefaultUrl(),
            ],
        ],
        'image' => [$jsonLdImageAbs],
    ];

    $jsonLdBreadcrumbs = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Início',
                'item' => route('homepage'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Blog',
                'item' => route('blog.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $post->category->name,
                'item' => route('blog.category', $post->category->slug),
            ],
            [
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $post->title,
                'item' => $canonicalPostUrl,
            ],
        ],
    ];
@endphp

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('meta_keywords', $post->meta_keywords ? implode(', ', $post->meta_keywords) : '')
@section('meta_author', $post->author->name ?? \App\Support\SiteBranding::siteName())

@section('canonical', $canonicalPostUrl)

@section('og_type', 'article')
@section('og_title', $post->title)
@section('og_description', $postOgDescription)
@section('og_image', $postOgImageMeta)

@section('twitter_title', $post->title)
@section('twitter_description', $postOgDescription)
@section('twitter_image', $postOgImageMeta)

@push('article_meta')
<meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
<meta property="article:section" content="{{ $post->category->name }}">
@if($post->author)
<meta property="article:author" content="{{ $post->author->name }}">
@endif
@endpush

@push('json_ld')
<script type="application/ld+json">{!! json_encode($jsonLdBlogPosting, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($jsonLdBreadcrumbs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<article class="pb-4" itemscope itemtype="https://schema.org/BlogPosting">
    @include('blog::public.partials.article-hero')

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 lg:py-12">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-12">
            <div class="space-y-8 lg:col-span-8 max-w-[52rem] lg:mx-auto xl:mx-0 xl:max-w-none">
                @include('blog::public.partials.article-main')
            </div>
            <aside class="lg:col-span-4" aria-label="Barra lateral">
                @include('blog::public.partials.blog-public-sidebar')
            </aside>
        </div>
    </div>


</article>


<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Imagem ampliada" class="max-w-full max-h-full object-contain">
    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">×</button>
</div>
@endsection

@push('styles')
<style>
/* Custom animations and styles for the blog */
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

/* Improve prose styling */
.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    font-weight: 700;
    line-height: 1.2;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose h1 { font-size: 2.25rem; }
.prose h2 { font-size: 1.875rem; }
.prose h3 { font-size: 1.5rem; }

.prose p {
    margin-bottom: 1.5rem;
    line-height: 1.7;
}

.prose ul, .prose ol {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose blockquote {
    border-left: 4px solid rgb(16 185 129);
    padding-left: 1rem;
    font-style: italic;
    background: rgb(236 253 245);
    padding: 1rem;
    border-radius: 0.5rem;
    margin: 1.5rem 0;
}

.dark .prose blockquote {
    background: rgb(4 47 46);
    border-left-color: rgb(16 185 129);
}

/* Gallery hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Modal improvements */
#gallery-modal img {
    transition: transform 0.3s ease;
}

#gallery-modal img:hover {
    transform: scale(1.02);
}
</style>
@endpush

@push('scripts')
<script>
let currentSlide = 0;
const totalSlides = {{ count($post->gallery_images ?? []) }};

// Gallery Carousel Functions
function initGalleryCarousel() {
    if (totalSlides <= 1) return;

    const carousel = document.getElementById('gallery-carousel');
    const prevBtn = document.getElementById('gallery-prev');
    const nextBtn = document.getElementById('gallery-next');
    const indicators = document.querySelectorAll('.gallery-indicator');

    function updateCarousel() {
        if (carousel) {
            carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        // Update indicators
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('bg-opacity-100', index === currentSlide);
            indicator.classList.toggle('bg-opacity-50', index !== currentSlide);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1;
            updateCarousel();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
            updateCarousel();
        });
    }

    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentSlide = index;
            updateCarousel();
        });
    });

    // Auto-play (optional)
    setInterval(() => {
        currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
        updateCarousel();
    }, 5000);
}

// Gallery Modal Functions
function openGalleryModal(startIndex = 0) {
    currentSlide = startIndex;
    const modal = document.getElementById('gallery-modal');
    if (modal) {
        modal.classList.remove('hidden');
        updateModalSlide();
        document.body.style.overflow = 'hidden';

        // Add entrance animation
        modal.querySelector('#gallery-modal-content')?.classList.add('animate-fade-in');
    }
}

function closeGalleryModal() {
    const modal = document.getElementById('gallery-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function updateModalSlide() {
    const slides = document.querySelectorAll('.gallery-modal-slide');
    const thumbnails = document.querySelectorAll('.gallery-thumbnail');
    const counter = document.getElementById('image-counter');

    slides.forEach((slide, index) => {
        slide.classList.toggle('block', index === currentSlide);
        slide.classList.toggle('hidden', index !== currentSlide);
    });

    // Update thumbnails
    thumbnails.forEach((thumb, index) => {
        thumb.classList.toggle('bg-opacity-100', index === currentSlide);
        thumb.classList.toggle('bg-opacity-50', index !== currentSlide);
    });

    // Update counter
    if (counter) {
        counter.textContent = currentSlide + 1;
    }
}

function goToSlide(index) {
    currentSlide = index;
    updateModalSlide();
}

// Modal Navigation
document.addEventListener('DOMContentLoaded', function() {
    initGalleryCarousel();

    // Modal controls
    const closeBtn = document.getElementById('close-gallery-modal');
    const modalPrev = document.getElementById('modal-prev');
    const modalNext = document.getElementById('modal-next');
    const modal = document.getElementById('gallery-modal');

    if (closeBtn) {
        closeBtn.addEventListener('click', closeGalleryModal);
    }

    if (modalPrev && totalSlides > 1) {
        modalPrev.addEventListener('click', () => {
            currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1;
            updateModalSlide();
        });
    }

    if (modalNext && totalSlides > 1) {
        modalNext.addEventListener('click', () => {
            currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
            updateModalSlide();
        });
    }

    // Close modal on background click
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeGalleryModal();
            }
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                closeGalleryModal();
            } else if (e.key === 'ArrowLeft' && totalSlides > 1) {
                currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1;
                updateModalSlide();
            } else if (e.key === 'ArrowRight' && totalSlides > 1) {
                currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0;
                updateModalSlide();
            }
        }
    });
});

// Make functions globally available
window.openGalleryModal = openGalleryModal;
window.goToSlide = goToSlide;
</script>
@endpush

@push('scripts')
<script>
function copyToClipboard(evt, text) {
    const button = evt.currentTarget;
    navigator.clipboard.writeText(text).then(function() {
        const originalHtml = button.innerHTML;
        button.innerHTML = originalHtml.replace('Copiar Link', 'Copiado!');
        button.classList.add('bg-green-600');
        setTimeout(() => {
            button.innerHTML = originalHtml;
            button.classList.remove('bg-green-600');
        }, 2000);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const shareBtn = document.getElementById('blog-native-share');
    if (shareBtn && navigator.share) {
        shareBtn.hidden = false;
        shareBtn.addEventListener('click', function() {
            navigator.share({
                title: @json($post->title),
                text: @json($post->title),
                url: @json($canonicalPostUrl),
            }).catch(function() {});
        });
    }
});

function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Comment reply functions
function showReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.classList.remove('hidden');
    }
}

function hideReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.classList.add('hidden');
    }
}
</script>
@endpush
