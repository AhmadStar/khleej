<div class="panel panel-default">
    <div class="panel-title">
        <p>{{ $config['name'] }}</p>
    </div>
    <div class="panel-content">
        <p>{!! do_shortcode(BaseHelper::clean($config['content'])) !!}</p>
    </div>
</div>
