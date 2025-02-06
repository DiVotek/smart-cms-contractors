<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SmartCms\Contractors\Models\Contractor;
use SmartCms\Store\Models\Currency;
use SmartCms\Store\Models\Product;

return new class extends Migration
{
   public function up(): void
   {
      Schema::create(Contractor::getDb(), function (Blueprint $table) {
         $table->id();
         $table->string('name');
         $table->foreignIdFor(Currency::class);
         $table->decimal('rate', 10, 2);
         $table->timestamps();
      });
      $contractor = Contractor::query()->create([
         'name' => 'Default',
         'currency_id' => Currency::query()->first()->id ?? 0,
         'rate' => 1,
      ]);
      Product::query()->update([
         'contractor_id' => $contractor->id,
      ]);
   }

   public function down(): void
   {
      Schema::dropIfExists(Contractor::getDb());
   }
};
