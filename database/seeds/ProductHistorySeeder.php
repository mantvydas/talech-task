<?php

use App\Models\ProductHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        ProductHistory::truncate();
        Schema::enableForeignKeyConstraints();
        factory(ProductHistory::class, 200)->create();
    }
}
