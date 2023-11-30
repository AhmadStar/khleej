@if($post)

<div class="border-related">
    <p class="related-artciles">{{ __("مقالة ذات صلة")}}</p>
    <div class="row">
        <div class="col-lg-4 col-sm-4">
            <a href="{{ $post->url }}">
                <img src="{{ RvMedia::getImageUrl($post->image) }}" alt="{{ $post->name }}">
            </a>
        </div>
        <div class="col-lg-8 col-sm-8">
            <a href="{{ $post->url }}" class="related-color">{{ $post->name }}</a>
            <br>
            {{ $post->description }}
        </div>
    </div>
</div>

<style>
    .related-artciles{
        font-size: 24px;
        font-weight: bold;
    }

    .border-related{
        border: 1px solid #dee2e6!important;
        padding: 15px;
    }

    .related-color{
        color: #d4145a !important;
        font-weight: bold;
    }
</style>

@endif
