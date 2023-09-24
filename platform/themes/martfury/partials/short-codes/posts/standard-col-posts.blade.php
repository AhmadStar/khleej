@foreach ($posts_col as  $key=>$post)
<article id="article-{{$rand}}-{{$key}}">
    <div class="row">
        <div class="col-auto pe-0">
            <div class="date">
                <span
                    class="day text-color-dark font-weight-extra-bold">{{ $post->created_at->translatedFormat('d') }}</span>
                    <span
                    class="month bg-color-primary font-weight-semibold text-color-light text-1">{{  $post->created_at->translatedFormat('M') }}</span>
                    <span
                    class="year font-weight-semibold text-color-light text-1">{{  $post->created_at->translatedFormat('Y') }}</span>
            </div>
        </div>
        <div class="col ps-1">
            <h4 class="line-height-3 text-3"><a href="{{ $post->url }}"
                    class="text-dark">{{ $post->name }}</a></h4>

        </div>
    </div>
</article>
@endforeach
