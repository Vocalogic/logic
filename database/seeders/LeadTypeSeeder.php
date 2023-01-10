<?php

namespace Database\Seeders;

use App\Models\LeadType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (LeadType::count() == 0)
        {
            $type = (new LeadType)->create([
                'name' => "Phone Service"
            ]);
            $type->refresh();
            $type->term()->create([
                'name' => "Phone Service Terms",
                'body' => view('template_holders.tos')->render()
            ]);
        }
    }
}
