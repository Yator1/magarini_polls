@if(session('success'))
    {{--Success Alert--}}
    <div class="alert alert-success alert-border-left alert-dismissible fade show"
         role="alert">
        <i
            class="ri-notification-off-line me-3 align-middle fs-16"></i><strong>Success</strong>
        <p>{!! session('success') !!}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
    </div>

@elseif(session('error'))
    {{--Error Alert--}}
    <div class="alert alert-danger alert-border-left alert-dismissible fade show mb-xl-0"
         role="alert">
        <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Error</strong>
        <p>{!! session('error') !!}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
    </div>

@elseif(session('message'))
    {{--Warning Alert--}}
    <div class="alert alert-warning alert-border-left alert-dismissible fade show"
         role="alert">
        <i class="ri-mail-line me-3 align-middle fs-16"></i><strong>Message</strong>
        <p>{!!session('message')!!}</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
    </div>

@endif
