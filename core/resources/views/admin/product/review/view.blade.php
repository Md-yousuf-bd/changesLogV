@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="row mb-none-30 justify-content-center">
                <div class="col-xl-4 col-md-6 mb-30">
                    <div class="card overflow-hidden box--shadow1">
                        <div class="card-body">
                            <h5 class="mb-20 text-muted"> @lang('Review information') </h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Product Name') <span class="fw-bold"><a href="{{ route('admin.products.edit', $review->product->id) }}">{{ __($review->product->name) }}</a></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Reviewer') <span class="fw-bold"><a href="{{ route('admin.users.detail', $review->user->id) }}">{{ $review->user->username }}</a></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Rating') <span class="fw-bold">{{ $review->rating }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Date of review') <span class="fw-bold">{{ showDateTime($review->created_at) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Status') <span class="fw-bold">@php echo $review->statusBadge @endphp</span>
                                </li>
                                @if ($review->reject_reason && $review->status != Status::REVIEW_REJECTED)
                                    <li class="list-group-item">
                                        <span class="d-block pb-2 fw-bold">@lang('Rejected Reason :')</span>
                                        <span>
                                            {{ $review->reject_reason }}
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-6 mb-30">
                    <div class="card overflow-hidden box--shadow1">
                        <div class="card-body">
                            <div class="product-reviews">
                                <ul class="product-review-list">
                                    <li class="product-review-list-item">
                                        <div class="product-review-list-item__header d-flex flex-wrap align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">

                                                <img class="thumb" src="{{ getAvatar(getFilePath('userProfile') . '/' . $review->user->image) }}" alt="profile image">
                                                <h6 class="name">{{ $review->user->fullname }}</h6>
                                            </div>
                                        </div>
                                        <p class="desc">
                                            @php echo nl2br($review->review) @endphp
                                        </p>

                                        @if (!blank($review->productReviewImage))
                                            <div class="review-attachment">
                                                @foreach ($review->productReviewImage ?? [] as $reviewImage)
                                                    <a class="review-attachment__img review-gallery" href="{{ getImage(getFilePath('review') . '/' . $reviewImage->image) }}">
                                                        <img src="{{ getImage(getFilePath('review') . '/' . $reviewImage->image) }}" alt="reply">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </li>
                                    @php
                                        $reply = $review->productReviewReply;
                                        $oldImages = [];
                                    @endphp
                                    @if ($reply)
                                        <li class="product-review-list-item admin-reply">
                                            <div class="product-review-list-item__header d-flex flex-wrap align-items-center justify-content-between mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    @php
                                                        $name = @$reply->admin->name;
                                                        $imagePath = getFilePath('adminProfile') . '/' . @$reply->admin->image;
                                                    @endphp
                                                    <img class="thumb" src="{{ getAvatar($imagePath) }}" alt="profile image">
                                                    <h6 class="name">{{ $name }}</h6>
                                                </div>
                                                <span class="time">{{ diffForHumans($reply->updated_at) }}</span>
                                            </div>
                                            <p class="desc">
                                                @php echo nl2br($reply->comment) @endphp
                                            </p>

                                            @if (!blank($reply->productReviewReplyImage))
                                                <div class="review-attachment">
                                                    @foreach ($reply->productReviewReplyImage ?? [] as $reviewReplyImage)
                                                        @php
                                                            array_push($oldImages, [
                                                                'id' => $reviewReplyImage->id,
                                                                'src' => getImage(getFilePath('review') . '/' . $reviewReplyImage->image),
                                                            ]);
                                                        @endphp

                                                        <a class="review-attachment__img review-gallery" href="{{ getImage(getFilePath('review') . '/' . $reviewReplyImage->image) }}">
                                                            <img src="{{ getImage(getFilePath('review') . '/' . $reviewReplyImage->image) }}" alt="reply">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </li>
                                    @endif
                                </ul>
                                @if ($reply)
                                    <div class="text-end">
                                        <button class="btn btn--primary editReplay"><i class="las la-reply"></i>@lang('Edit Reply to Review')</button>
                                    </div>
                                @endif
                            </div>

                            <form action="{{ route('admin.products.reviews.reply', $review->id) }}" class="reply--form {{ $review->productReviewReply ? 'd-none' : '' }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <textarea name="comment" class="form-control" rows="3" placeholder="@lang('Write a reply of the review')">{{ old('comment', @$reply->comment) }}</textarea>
                                </div>
                                <x-file-uploader :extensions="['.png', '.jpg', '.jpeg']" fileName="images" maxFile="10" />

                                <div class="text-end">
                                    @if ($reply)
                                        <button type="button" class="btn btn--warning reviewCancelBtn"><i class="las la-times"></i>@lang('Cancel')</button>
                                    @endif
                                    <button type="submit" class="btn btn--primary"><i class="las la-reply"></i>@lang('Reply to Review')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="reviewRejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Review')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.products.reviews.reject', $review->id) }}" method="POST" id="confirmation-form">
                    @csrf
                    <div class="modal-body">
                        <textarea name="reject_reason" rows="4" placeholder="Enter reject reason" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />

    <div class="modal fade custom--modal" id="rejectReasonModal" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title m-0">@lang('Reason for Rejected')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="reason-box m-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--primary" data-bs-dismiss="modal">@lang('Ok')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    @if ($review->status != Status::REVIEW_APPROVED)
        <button class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{ route('admin.products.reviews.approve', $review->id) }}" data-question="@lang('Are you sure to approve this review?')">
            <i class="las la-check"></i>@lang('Approve')
        </button>
    @endif
    @if ($review->status != Status::REVIEW_REJECTED)
        <button class="btn btn-sm btn-outline--danger rejectBtn"><i class="las la-times"></i>@lang('Reject')</button>
    @endif
    <x-back route="{{ route('admin.products.reviews.index') }}" />
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/xzoom/magnific-popup.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/xzoom/magnific-popup.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            let oldImages = @json($oldImages);
            multipleImageUpload(oldImages)

            $('.review-gallery').on("click", function() {
                $('.review-attachment').each(function() {
                    $(this).magnificPopup({
                        delegate: 'a',
                        type: 'image',
                        gallery: {
                            enabled: true
                        }
                    });
                });
            })

            $('.rejectBtn').on('click', function() {
                let modal = $('#reviewRejectModal');
                modal.modal('show');
            });

            $('.reasonBtn').on('click', function() {
                var modal = $('#rejectReasonModal');
                modal.find('.reason-box').html(($(this).data('reason')));
                modal.modal('show');
            });

            $('.editReplay').on('click', function() {
                $('.reply--form').removeClass('d-none');
                $('.admin-reply').addClass('d-none');
                scrollDown();
            })

            $('.reviewCancelBtn').on('click', function() {
                $('.reply--form').addClass('d-none');
                $('.admin-reply').removeClass('d-none');
                scrollDown();
            })

            function scrollDown() {
                $(document).ready(function() {
                    setTimeout(function() {
                        $(".product-reviews").scrollTop($(".product-reviews")[0].scrollHeight);
                    }, 100);
                });
            }

            scrollDown();


        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .image-uploader {
            min-height: 8rem;
            border: 1px dotted #ccc;
            border-radius: 5px;
        }

        .product-reviews {
            max-height: 600px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.05) rgba(0, 0, 0, 0.1);
            margin-bottom: 16px;
        }

        .product-review-list-item {
            border: 1px solid #ebebebb0;
            border-radius: 12px;
            padding: 16px;
        }

        .product-review-list-item.admin-reply .review-attachment__img {
            border-color: rgba(0, 0, 0, 0.1);
        }

        .product-review-list-item.admin-reply {
            background: #4534ff09;
            border: 1px solid #4534ff4b;
        }

        .product-review-list-item {
            margin-bottom: 12px;
        }

        .product-review-list-item__header {
            padding-bottom: 10px;
            border-bottom: 1px solid #ebebebb0;
        }

        .product-review-list-item .thumb {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        @media screen and (max-width: 575px) {
            .product-review-list-item .thumb {
                width: 35px;
                height: 35px;
            }
        }

        .reply--form {
            border: 1px solid #4634ff40;
            padding: 10px;
            border-radius: 10px;
        }

        .product-review-list-item .review-attachment {
            margin-top: 16px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .product-review-list-item .review-attachment__img {
            width: 80px;
            height: 80px;
            border: 1px solid #ebebebb0;
            border-radius: 3px;
            padding: 5px;
            display: block;
        }

        .product-review-list-item .review-attachment__img img {
            width: 100% !important;
            height: 100%;
            display: block;
            object-fit: cover;
        }
    </style>
@endpush
