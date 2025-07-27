<?php

namespace App\Services;

use App\Models\Item;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class ItemService
{

    public function generateFakeData()
    {
        $fake = Factory::create();

        DB::transaction(function () use ($fake){

            $fakeData = [];

            for ($i = 0; $i < 1000; $i++){
                $fakeData[] = [
                    'name' => $fake->name,
                    'discription' => $fake->sentence,
                    'status' => $i % 2 === 0 ? 'Allowed' : 'Prohibited',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('items')->insert($fakeData);
        });
    }

    public function clearTable()
    {
        Item::truncate();
    }

}