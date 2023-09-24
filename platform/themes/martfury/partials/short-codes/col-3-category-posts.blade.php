<section class="recent-posts section pt-50 pb-50 bg-lightgray">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class=" mb-4 mb-lg-0">
                    <div class="art-box" id="list-1-">
                        <h4 class="title news-list">
                            {{ $title }}

                        </h4>
                        {!! Theme::partial('short-codes.posts.standard-col-posts', ['posts_col' => $posts, 'rand' => 765]) !!}

                    </div>

                </div>
            </div>
            <div class="col-lg-4">
                <div class=" mb-4 mb-lg-0">
                    <div class="art-box" id="list-1-">
                        <h4 class="title news-list">
                            {{ $title2 }}

                        </h4>
                        {!! Theme::partial('short-codes.posts.standard-col-posts', ['posts_col' => $posts2, 'rand' => 765]) !!}

                    </div>

                </div>
            </div>
            <div class="col-lg-4">
                <div class=" mb-4 mb-lg-0">
                    <div class="art-box" id="list-1-">
                        <h4 class="title news-list">
                            {{ $title3 }}

                        </h4>
                        {!! Theme::partial('short-codes.posts.standard-col-posts', ['posts_col' => $posts3, 'rand' => 765]) !!}

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

