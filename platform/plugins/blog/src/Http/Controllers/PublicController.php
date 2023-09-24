<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Services\BlogService;
use Botble\Marketplace\Models\Store;

use Botble\Slug\Models\Slug;
use Botble\Theme\Events\RenderingSingleEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Response;
use SeoHelper;
use SlugHelper;
use Theme;
use RvMedia;

use Botble\Base\Http\Responses\BaseHttpResponse;

class PublicController extends Controller
{
    public function importVendors(Request $request, BaseHttpResponse $response)
    {
        \BaseHelper::maximumExecutionTimeAndMemoryLimit();


        //$Stores = Store::where('id', '>', 0)->get();
        //foreach ($Stores as $Store)

        //dd($Store);


        $path = "/public/themes/martfury/json/thesaudifood-3.json";// . $request->input('file');
        if ($path) {
            $json = json_decode(\File::get(base_path() . $path), true);

        }

       // dd($json);
        $counter=0;
        foreach ($json as $item) {

           // if($counter>0)//{dd($item);}
            try {

                $logo = '';
                if(isset($item['image'])){
                    $result = RvMedia::uploadFromUrl($item['image'], 9);
                    if ($result['error'] == false && isset($result['data'])) {
                        $logo = $result['data']->url;
                    }
                }

                $content = '';
                $content = isset($item['content']) ? $item['content'] : " ";
//                if (isset($item['cat']))
//                    $content .= implode(" ", $item['cat']);
                // dd($item);
                $Store=Store::updateOrCreate([
                    'name' => $item['title']
                ], [
                    'name' => $item['title'],
                    'city' => isset($item['city']) ? $item['city'] : " ",
                    'country' => "SA",
                    'logo' => $logo,
                    'content' => $content,
                ]);
                Slug::create([
                    'reference_type' => Store::class,
                    'reference_id' => $Store->id,
                    'key' => Str::slug($Store->name, '-', !SlugHelper::turnOffAutomaticUrlTranslationIntoLatin() ? 'ar' : false),
                    'prefix' => SlugHelper::getPrefix(Post::class),
                ]);

            } catch (Exception $exception) {
                dd($exception);

            }
            $counter++;
//            dd($item);

        }
        dd($json);
    }

    public function importPosts(Request $request, BaseHttpResponse $response)
    {
        \BaseHelper::maximumExecutionTimeAndMemoryLimit();
        $Posts = Post::where('id', '>', 0)->get();
        foreach ($Posts as $Post){
            $result = RvMedia::uploadFromUrl($Post->image, 9);
                if ($result['error'] == false && isset($result['data'])) {
                    $image = $result['data']->url;
                    $Post->image=$image;
                    $Post->save();
                    //dd($Post);
                }
        }
        dd($Post);
        //daleeeel-articles.json
        $path = "/public/themes/martfury/json/" . $request->input('file');
        if ($path) {
            $json = json_decode(\File::get(base_path() . $path), true);

        }
//        foreach ($json as $item) {
//            if (isset($item['cat'])) {
//                $cats = Category::updateOrCreate([
//                    'name' => $item['cat']
//                ], [
//                    'name' => $item['cat'],
//                    'parent_id' => 0,
//                    'author_id' => 1,
//                    'status' => BaseStatusEnum::PUBLISHED,
//                ]);
//                Slug::create([
//                    'reference_type' => Category::class,
//                    'reference_id' => $cats->id,
//                    'key' => Str::slug($cats->name, '-', !SlugHelper::turnOffAutomaticUrlTranslationIntoLatin() ? 'ar' : false),
//                    'prefix' => SlugHelper::getPrefix(Category::class),
//                ]);
//            }
//
//
//        }
        $categories=[];
        $cats=Category::all();
        foreach ($cats as $cat)
            $categories[$cat->name]=$cat->id;
//        dd($cats);
        foreach ($json as $item) {

            try {

                $image = '';
//                $result = RvMedia::uploadFromUrl($item['image'], 9);
//                if ($result['error'] == false && isset($result['data'])) {
//                    $image = $result['data']->url;
//                }
                $content = '';
                $content = isset($item['website']) ? $item['website'] : " ";
                if (isset($item['cat']))
                    $item['cat'];

                $post = Post::updateOrCreate([
                    'name' => $item['title']
                ], [
                    'status' => BaseStatusEnum::PENDING,
                    'author_id' => 1,
                    'name' => $item['title'],
                    'content' => isset($item['text']) ? $item['text'] : " ",
                    'image' => "https://www.daleeeel.com".$item['img'],

                ]);
                $post->categories()->attach($categories[$item['cat']]);
                Slug::create([
                    'reference_type' => Post::class,
                    'reference_id' => $post->id,
                    'key' => Str::slug($post->name, '-', !SlugHelper::turnOffAutomaticUrlTranslationIntoLatin() ? 'ar' : false),
                    'prefix' => SlugHelper::getPrefix(Post::class),
                ]);
                //dd( $post);
            } catch (Exception $exception) {


            }

//            dd($item);

        }
        dd($json);
    }

    /**
     * @param Request $request
     * @param PostInterface $postRepository
     * @return Response
     */
    public function getSearch(Request $request, PostInterface $postRepository)
    {
        $query = $request->input('q');

        $title = __('Search result for: ":query"', compact('query'));
        SeoHelper::setTitle($title)
            ->setDescription($title);

        $posts = $postRepository->getSearch($query, 0, 12);

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add($title, route('public.search'));

        return Theme::scope('search', compact('posts'))
            ->render();
    }

    /**
     * @param string $slug
     * @param BlogService $blogService
     * @return RedirectResponse|Response
     */
    public function getTag($slug, BlogService $blogService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Tag::class));

        if (!$slug) {
            abort(404);
        }

        $data = $blogService->handleFrontRoutes($slug);

        if (isset($data['slug']) && $data['slug'] !== $slug->key) {
            return redirect()->to(route('public.single', SlugHelper::getPrefix(Tag::class) . '/' . $data['slug']));
        }

        event(new RenderingSingleEvent($slug));

        return Theme::scope($data['view'], $data['data'], $data['default_view'])
            ->render();
    }

    /**
     * @param string $slug
     * @param BlogService $blogService
     * @return RedirectResponse|Response
     */
    public function getPost($slug, BlogService $blogService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Post::class));

        if (!$slug) {
            abort(404);
        }

        $data = $blogService->handleFrontRoutes($slug);

        if (isset($data['slug']) && $data['slug'] !== $slug->key) {
            return redirect()->to(route('public.single', SlugHelper::getPrefix(Post::class) . '/' . $data['slug']));
        }

        event(new RenderingSingleEvent($slug));

        Theme::asset()->add('ckeditor-content-styles', 'vendor/core/core/base/libraries/ckeditor/content-styles.css');

        return Theme::scope($data['view'], $data['data'], $data['default_view'])
            ->render();
    }

    /**
     * @param string $slug
     * @param BlogService $blogService
     * @return RedirectResponse|Response
     */
    public function getCategory($slug, BlogService $blogService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Category::class));

        if (!$slug) {
            abort(404);
        }

        $data = $blogService->handleFrontRoutes($slug);

        if (isset($data['slug']) && $data['slug'] !== $slug->key) {
            return redirect()->to(route('public.single', SlugHelper::getPrefix(Category::class) . '/' . $data['slug']));
        }

        event(new RenderingSingleEvent($slug));

        return Theme::scope($data['view'], $data['data'], $data['default_view'])
            ->render();
    }
}
