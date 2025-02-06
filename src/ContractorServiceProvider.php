<?php

namespace SmartCms\Contractors;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SmartCms\Contractors\Admin\Actions\Navigation\Resources;
use SmartCms\Contractors\Events\OptionValueCreate;
use SmartCms\Contractors\Events\OptionValueMutate;
use SmartCms\Contractors\Events\ProductCreateForm;
use SmartCms\Contractors\Events\ProductCreating;
use SmartCms\Contractors\Events\ProductPrice;
use SmartCms\Contractors\Events\ProductUpdateForm;
use SmartCms\Contractors\Events\ProductUpdating;
use SmartCms\Contractors\Models\Contractor;
use SmartCms\Store\Models\Product;

class ContractorServiceProvider  extends ServiceProvider
{
   public function register()
   {
      $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
      Event::listen('cms.admin.navigation.resources', Resources::class);
   }

   public function boot()
   {
      if (!setting('contractor.enabled', true)) {
         return;
      }
      Product::resolveRelationUsing('contractor', function ($product) {
         return $product->belongsTo(Contractor::class);
      });
      Event::listen('cms.admin.product.create-form', ProductCreateForm::class);
      Event::listen('cms.admin.product.update-form', ProductUpdateForm::class);
      Event::listen('cms.admin.product.creating', ProductCreating::class);
      Event::listen('cms.admin.product.updating', ProductUpdating::class);
      Event::listen('cms.admin.option-value.mutate', OptionValueMutate::class);
      Event::listen('cms.admin.option-value.create', OptionValueCreate::class);
   }
}
