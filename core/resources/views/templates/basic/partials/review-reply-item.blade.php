@php
    $userImage = $reply->user ? getAvatar(getFilePath('userProfile') . '/' . $reply->user->image) : siteFavicon();
    $imagePath = $reply->user ? getFilePath('userProfile') : getFilePath('adminProfile');
    $name = $reply->user ? $reply->user->fullname : gs('site_name');

@endphp
<div class="review-area mb-3 mb-md-4" data-reply-id="{{ $reply->id }}">
    <div class="review-item d-flex ">
        <div class="thumb">
            <img src="{{ $userImage }}" data-src="{{ $userImage }}" class="lazyload" alt="review">
        </div>
        <div class="content">
            <div class="entry-meta">
                <h6 class="posted-by">
                    <span class="d-inline-flex flex-wrap align-items-center gap-1">{{ __($name) }}</span>
                    <span class="posted-on fs-14">{{ diffForHumans($reply->created_at) }}</span>
                    @if ($reply->user_id == auth()->id())
                        <button class="edit-reply-btn reply__btn" type="button" data-review_location="{{ asset(getFilePath('review')) }}" data-reply-id="{{ $reply->id }}" data-review-image='@json($reply->productReviewReplyImage)' data-comment="{{ $reply->comment }}" data-action="{{ route('user.review.reply', [$reply->product_review_id, $reply->id]) }}">
                            <i class="las la-edit"></i>
                        </button>
                    @endif
                </h6>
            </div>
            <div class="d-flex gap-3">
                <p class="review-item__reply-msg mb-0">
                    @php echo nl2br($reply->comment) @endphp
                </p>
            </div>
            @if (!blank($reply->productReviewReplyImage))
                <div class="review--image flex-wrap review-gallery mt-3" id="galleryParent4">
                    @foreach ($reply->productReviewReplyImage as $reviewReplyImage)
                        <a href="{{ getImage(getFilePath('review') . '/' . $reviewReplyImage->image) }}">
                            <img src="{{ getImage(getFilePath('review') . '/' . $reviewReplyImage->image) }}" alt="Product review">
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
