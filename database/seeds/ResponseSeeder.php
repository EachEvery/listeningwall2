<?php

use Illuminate\Database\Seeder;
use App\Repositories\ResponseRepository as Responses;
use App\Repositories\SourceRepository as Sources;
use Faker\Generator;
use Illuminate\Support\Facades\DB;

class ResponseSeeder extends Seeder
{
    public function __construct(
        Sources $sources,
        Responses $responses,
        VideoFixture $videos,
        ThumbnailFixture $thumbnails,
        Generator $faker
    ) {
        $this->sourceIds = $sources->whereEnabled()->pluck('id');
        $this->responses = $responses;
        $this->faker = $faker;

        $this->videos = collect($videos->get());
        $this->thumbnails = collect($thumbnails->get());
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {

        DB::table('responses')->delete();

        for ($i = 0; $i < 100; ++$i) {
            $hasVideo = $this->faker->boolean();

            $this->responses->create([
                'title' => $this->faker->words(2, true),
                'author' => $this->faker->name,
                'words' => json_encode($this->getWords()),
                'video' => $hasVideo ? $this->videos->random() : null,
                'photo' => $hasVideo ? $this->thumbnails->random() : null,
            ]);
        }
    }

    public function getWords()
    {
        $count = $this->faker->numberBetween(5, 10);
        $words = [];

        for ($i = 0; $i < $count; ++$i) {
            $words[] = [
                'word' => $this->faker->word(),
                'sourceId' => $this->sourceIds->random(),
                'left' => $this->faker->numberBetween(0, 170),
                'row' => sprintf('row-%s', $this->faker->numberBetween(1, 16)),
            ];
        }

        return $words;
    }
}
