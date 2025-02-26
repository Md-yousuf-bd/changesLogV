@props(['oldImages' => [], 'extensions' => ['*'], 'fileName' => 'images', 'maxFile' => 10])
@php
    $showExtensions = implode(', ', $extensions);
@endphp

<div class="input-images"></div>
<small class="form-text text-muted mt-1 d-block">
    <i class="las la-info-circle"></i> @lang('Supported files:') {{ $showExtensions }} @lang('and you can upload a maximum of') {{ $maxFile }} @lang('images.')
</small>

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";

        let extensions = @json($extensions);
        let fileName = '{{ $fileName }}';
        let maxFile = '{{ $maxFile }}';

        function multipleImageUpload(preloaded = []) {
            $('.input-images').html('');
            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: fileName,
                preloadedInputName: 'old',
                maxFiles: maxFile,
                extensions: extensions,
                label : "@lang('Drag & Drop Images here or click to browse')"
            });
        }
    </script>
@endpush
