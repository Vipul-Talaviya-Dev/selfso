<?php

use Illuminate\Database\Seeder;
use App\Models\AppVersion;

class AppVersionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = ['android', 'ios'];

    	foreach ($contents as $key => $content) {
	        AppVersion::create([
	        	'type' => $content,
	        	'version' => 1,
	        	'user_type' => 1,
	        ]);
    	}
    }
}
