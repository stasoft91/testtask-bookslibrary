<?php

namespace App\Console\Commands;

use App\Book;
use App\Helpers\ImageDownloader;
use Illuminate\Console\Command;

class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:books {xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse books from .xml';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $xmlBooks = simplexml_load_file($this->argument('xml'));

        $barPages = $this->output->createProgressBar($xmlBooks->count());
        $barPages->start();

        foreach ($xmlBooks as $xmlBook) {
            $barPages->advance();
            if (Book::whereIsbn($xmlBook->ISBN)->count() > 0)
                continue;

            $book = new Book();
            $book->title = $xmlBook->title;
            $book->isbn = $xmlBook->ISBN;
            $book->description = $xmlBook->description;

            if ('' !== $xmlBook->image->__toString()) {
                $book->image = ImageDownloader::save($xmlBook->image);
            }

            $book->save();
        }
        $barPages->finish();
        echo PHP_EOL;
    }
}
