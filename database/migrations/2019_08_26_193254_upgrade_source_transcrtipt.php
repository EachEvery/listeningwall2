<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeSourceTranscrtipt extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sources', function (Blueprint $table) {
            $table->text('video_timestamps')->nullable();
        });

        DB::table('sources')->get()->each(function ($item) {
            $transcriptJson = str_replace('{"EN":"[{', '[{', $item->transcript);
            $transcriptJson = str_replace('"}]"}', '"}]', $transcriptJson);

            $ts = collect(json_decode($transcriptJson));

            $tsString = json_encode((object) [
                'EN' => $ts->pluck('text')->implode("\n\n"),
            ]);

            DB::table('sources')->where('id', $item->id)->update([
                'video_timestamps' => $ts->pluck('time_for_humans')->implode(','),
                'transcript' => $tsString,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
