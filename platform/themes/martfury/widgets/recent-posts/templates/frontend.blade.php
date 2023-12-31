@if (is_plugin_active('blog'))
    @php
        $posts = get_recent_posts($config['number_display']);
    @endphp
    @if ($posts->count())
        <aside class="widget widget--blog widget--recent-post">
            <p class="widget__title">{{ $config['name'] }}</p>
            <div class="widget__content">
                @foreach ($posts as $post)
                    <a href="{{ $post->url }}">{{ $post->name }}</a>
                @endforeach
            </div>
        </aside>
    @endif
@endif


