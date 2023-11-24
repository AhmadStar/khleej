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

class SyncGold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'sync-gold';// {country} {id}';
    protected $postRepository;



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync-gold';

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


        Gold::GoldPriceSync();
        Gold::GoldPriceSync('Saudi Arabia','https://www.150currency.com/ar/gold-rates-by-SAR.htm');
        Gold::GoldPriceSync('Emarat','https://www.150currency.com/ar/gold-rates-by-AED.htm');
        Gold::GoldPriceSync('Egypt','https://www.150currency.com/ar/gold-rates-by-EGP.htm');
        //Gold::GoldPriceSync(); 

    }


}
