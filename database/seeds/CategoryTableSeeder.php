<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Business', 'Education', 'Sport', 'Entertainment', 'People & Blog', 'Gaming'];
        foreach ($categories as $key => $category) {
	        Category::create([
	        	'name' => $category,
	        ]);
    	}
    }
}
