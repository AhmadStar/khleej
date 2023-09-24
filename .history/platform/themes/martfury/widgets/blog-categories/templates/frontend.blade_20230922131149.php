<aside class="widget widget--blog widget--categories">
    <p class="widget__title">{{ $config['name'] }}</p>
    <div class="widget__content">
        <ul>
            @foreach(app(\Botble\Blog\Repositories\Interfaces\CategoryInterface::class)->advancedGet(['condition' => ['status' => \Botble\Base\Enums\BaseStatusEnum::PUBLISHED], 'take' => $config['number_display'], 'with' => ['slugable'], 'withCount' => ['posts']]) as $category)
                <li><a href="{{ $category->url }}">{{ $category->name }} ({{ $category->posts_count }})</a></li>
            @endforeach
        </ul>
    </div>
</aside>
