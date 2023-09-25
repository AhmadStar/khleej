<?php

namespace App\Console\Commands;

use App\User;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Post;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Gold\Models\Gold;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UpdateGold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'update-gold:post';// {country} {id}';
    protected $postRepository;



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update post tr';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct( PostInterface $postRepository)
    {
        parent::__construct();

        $this->postRepository = $postRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $country ='Turkey';// $this->argument('country');
        $id = 10001;// $this->argument('id');
        echo '$country='.$country;
        echo '$id='.$id;
        // Now you can use the $locale variable in your logic
        Gold::updateGoldPost($id, $country);

    }


}
