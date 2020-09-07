<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use Illuminate\Console\Command;

class DeleteSoftDeletedRecordsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:soft-records {days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all soft deleted records which are older than given days';

    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $olderThan = $this->argument('days');
        $this->productService->permanentlyDeleteProducts($olderThan);
    }
}
