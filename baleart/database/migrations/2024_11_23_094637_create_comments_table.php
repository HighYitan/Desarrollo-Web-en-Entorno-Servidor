<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('comment', 5000);
            $table->decimal('score', 3, 2);
            $table->string("status", 1)->default('N');
            $table->foreignId('space_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
        DB::statement('
            CREATE TRIGGER update_scores_after_insert
            AFTER INSERT ON comments
            FOR EACH ROW
            BEGIN
                UPDATE spaces
                SET totalScore = totalScore + IFNULL(NEW.score, 0),
                    countScore = countScore + 1
                WHERE id = NEW.space_id;
            END;
        ');

        DB::statement('
            CREATE TRIGGER update_scores_after_update
            AFTER UPDATE ON comments
            FOR EACH ROW
            BEGIN
                UPDATE spaces
                SET totalScore = totalScore + IFNULL(NEW.score, 0) - IFNULL(OLD.score, 0)
                WHERE id = NEW.space_id;
            END;
        ');

        DB::statement('
            CREATE TRIGGER update_scores_after_delete
            AFTER DELETE ON comments
            FOR EACH ROW
            BEGIN
                UPDATE spaces
                SET totalScore = totalScore - IFNULL(OLD.score, 0),
                    countScore = countScore - 1
                WHERE id = OLD.space_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("update_scores_after_insert");
        Schema::dropIfExists("update_scores_after_update");
        Schema::dropIfExists("update_scores_after_delete");
        Schema::dropIfExists('comments');
    }
};
