@php
    Theme::layout('blog-sidebar')
@endphp

<div class="ps-post--detail sidebar">
    <div class="ps-post__header">
        <p>{{ $post->created_at->translatedFormat('M d, Y') }} @if ($post->author) / {{ __('By') }} {{ $post->author->name }} @endif / {{ __('in') }} @foreach($post->categories as $category) <a href="{{ $category->url }}">{{ $category->name }}</a> @if (!$loop->last) , @endif @endforeach</p>
    </div>
    <img class="alkhaleej-services" src="{{ RvMedia::getImageUrl($post->image, '', false, RvMedia::getDefaultImage()) }}" alt=" خدمات الخليج {{ $post->name }}" />
    <div class="ps-post__content" style="padding-top: 0;">
        {!! BaseHelper::clean($post->content) !!}
        @if (theme_option('facebook_comment_enabled_in_post', 'yes') == 'yes')
            <br />
            {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, Theme::partial('comments')) !!}
        @endif
    </div>
    <div class="ps-post__footer">
        @if (!$post->tags->isEmpty())
            <p class="ps-post__tags">{{ __('Tags') }}:
                @foreach ($post->tags as $tag)
                    <a href="{{ $tag->url }}">{{ $tag->name }}</a>
                @endforeach
            </p>
        @endif
        <div class="ps-post__social">
            <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->url) }}&title={{ $post->description }}" target="_blank"><i class="fa fa-facebook"></i></a>
            <a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($post->url) }}&summary={{ rawurldecode($post->description) }}" target="_blank"><i class="fa fa-twitter"></i></a>
            <a class="twitter" href="https://twitter.com/intent/tweet?url={{ urlencode($post->url) }}&text={{ $post->description }}" target="_blank"><i class="fa fa-linkedin"></i></a>
        </div>
    </div>
    @php $faqs = MetaBox::getMetaData($post, 'faq_schema_config',true); @endphp
    <div class="col-md-12">
        <div class="ps-section__content">
                <div class="ps-table--faqs">
                            @if($faqs)
                            <!-- Accordion -->
                            <h2>  أسئلة شائعة</h2>
                            <div class="style-1 style-instance fp-accordion">
                                <div class="accordion">
                                        @foreach($faqs as $faq)
                                            <h3 class="question">{!! clean($faq[0]['value']) !!}</h3>
                                            <!--<i class="fa fa-angle-down"></i>-->
                                            <div class="answer" style="opacity:0;height:1px;">
                                                <ul class="agent-contact-details">
                                                    <p itemprop="text">{!! clean($faq[1]['value']) !!}</p>
                                                </ul>
                                            </div>
                                        @endforeach
                                </div>
                            </div>
                             </div>
                            </div>
                            @endif

    </div>
    @php $relatedPosts = get_related_posts($post->id, 2); @endphp

    @if ($relatedPosts->count())
        <div class="ps-related-posts">
            <h3>{{ __('Related Posts') }}</h3>
            <div class="row">
                @foreach ($relatedPosts as $post)
                    <div class="col-sm-6 col-12">
                        <div class="ps-post">
                            <div class="ps-post__thumbnail">
                                <a class="ps-post__overlay" href="{{ $post->url }}"></a>
                                <img class="alkhaleej-services" src="{{ RvMedia::getImageUrl($post->image, 'small', false, RvMedia::getDefaultImage()) }}" alt=" خدمات الخليج {{ $post->name }}" />
                            </div>
                            <div class="ps-post__content" style="padding: 20px 0;">
                                <div class="ps-post__top">
                                    <div class="ps-post__meta">
                                        @foreach($post->categories as $category)
                                            <a href="{{ $category->url }}">{{ $category->name }}</a>
                                        @endforeach
                                    </div>
                                    <a class="ps-post__title" href="{{ $post->url }}">{{ $post->name }}</a>
                                </div>
                                <div class="ps-post__bottom">
                                    <p>{{ $post->created_at->translatedFormat('M d, Y') }} @if ($post->author) {{ __('by') }} {{ $post->author->name }} @endif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
{{--<script>--}}

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script><div id="qrcode-container"></div>--}}
{{--<script>--}}
    {{--// Get the container element--}}
    {{--const container = document.getElementById('qrcode-container');--}}

    {{--// QR code content--}}
    {{--const qrCodeContent = 'https://www.example.com';--}}

    {{--// Options for different layouts--}}
    {{--const optionsList = [--}}
        {{--{ width: 128, height: 128 },--}}
        {{--{ width: 256, height: 256 },--}}
        {{--{ width: 512, height: 512 },--}}
        {{--// Add more layout options as needed--}}
    {{--];--}}

    {{--// Generate QR codes for each layout option and append them to the container--}}
    {{--for (const options of optionsList) {--}}
        {{--const qrCode = new QRCode(container, {--}}
            {{--text: qrCodeContent,--}}
            {{--width: options.width,--}}
            {{--height: options.height,--}}
        {{--});--}}
    {{--}--}}
{{--</script>--}}