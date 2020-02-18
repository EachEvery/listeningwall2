<?php

use Illuminate\Database\Seeder;
use SourcePoemsFixture as SourcePoems;
use CategoriesFixture as Categories;
use App\Models\Source;
use App\Models\Category;
use Faker\Generator;

class SourceSeeder extends Seeder
{
    public function __construct(
        SourcePoems $poems,
        Categories $cats,
        TranscriptGenerator $tg,
        Generator $faker)
    {
        $this->poems = collect($poems->get());
        $this->cats = collect($cats->get());
        $this->faker = $faker;
        $this->tg = $tg;
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->cats = $this->cats->mapWithKeys(function ($cat) {
            return [
                $cat->id => Category::create([
                    'name' => $cat->name,
                    'color' => $cat->color,
                    'color_light' => $cat->color_light,
                ]),
            ];
        });

        $poemsWithCaptionFiles = $this->poems->filter(function ($poem) {
            return !empty($poem->captionFileUrl) && $this->tg->isValid($poem->captionFileUrl);
        });

        return $poemsWithCaptionFiles->map(function ($poem, $i) {
            $category = $this->cats->get($poem->tag);
            $transcript = $this->tg->generate($poem->captionFileUrl);

            return Source::create([
                'title' => $poem->title,
                'video' => $poem->videoUrl,
                'text' => $this->faker->paragraph,
                'thumbnail' => $poem->thumbnailImageUrl,
                'category_id' => $category->id,
                'transcript' => json_encode($transcript),
                'order' => $i,
            ]);
        });
    }
}
