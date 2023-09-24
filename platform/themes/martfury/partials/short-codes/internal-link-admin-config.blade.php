<div class="form-group">
    <label class="control-label">{{ __('Post') }}</label>
    <select name="post">
        @foreach ($posts as $post)
            <option value="{{ $post->id }}" @if (Arr::get($attributes, 'post') == $post->id) selected @endif>{{ $post->name }}
            </option>
        @endforeach
    </select>
</div>
