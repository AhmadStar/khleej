<aside class="widget widget--blog widget--tags">
    <p class="widget__title">{{ $config['name'] }}</p>
    <div class="widget__content">
        @foreach (get_popular_tags($config['number_display']) as $tag)
            <a href="{{ $tag->url }}">{{ $tag->name }}</a>
        @endforeach
    </div>
</aside>
