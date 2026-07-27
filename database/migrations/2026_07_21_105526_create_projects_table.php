<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_category_id')->nullable()->constrained('project_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client')->nullable();
            $table->string('location')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('project_value')->nullable();
            $table->string('area')->nullable();
            $table->text('scope')->nullable();
            $table->text('features')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
