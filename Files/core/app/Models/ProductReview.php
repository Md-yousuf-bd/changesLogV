<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProductReview extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productReviewImage()
    {
        return $this->hasMany(ProductReviewImage::class, 'product_review_id');
    }

    public function productReviewReply()
    {
        return $this->hasOne(ProductReviewReply::class, 'product_review_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::REVIEW_APPROVED);
    }


    public function viewStatusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->is_viewed == Status::YES) {
                    return '<span class="badge badge--success">' . trans('Viewed') . '</span>';
                } else {
                    return '<span class="badge badge--warning">' . trans('Not Viewed') . '</span>';
                }
            }
        );
    }

    public function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status == Status::REVIEW_PENDING) {
                    return '<span class="badge badge--warning">' . trans('Pending') . '</span>';
                } elseif ($this->status == Status::REVIEW_APPROVED) {
                    return '<span class="badge badge--success">' . trans('Approved') . '</span>';
                } else {
                    return '<div class="d-flex justify-content-center flex-wrap gap-1"><span class="badge badge--danger">' . trans('Rejected') . '</span>
                    <button data-bs-toggle="modal" data-bs-target="#rejectReasonModal" data-reason="' . $this->reject_reason . '" class="badge badge--danger reasonBtn"> <i class="las la-info-circle"></i> </button></div>';
                }
            }
        );
    }
}
