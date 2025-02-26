<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class ProductCollection extends Model
{
    protected $casts = ['product_ids' => 'array'];

    public function products()
    {
        return Product::published()->whereIn('id', $this->product_ids ?? [])
            ->withCount(['reviews' => function($review){
                $review->where('status', Status::REVIEW_APPROVED);
            }])
            ->withAvg(['reviews' => function($review1){
                $review1->where('status', Status::REVIEW_APPROVED);
            }], 'rating')
            ->with('brand:id,name', 'productVariants')->get();
    }
}
