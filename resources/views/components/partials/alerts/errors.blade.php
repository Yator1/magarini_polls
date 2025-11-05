@if($errors->any())
    <div class="alert alert-danger alert-border-left alert-dismissible fade show mb-xl-0"
         role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        <strong>Failed!</strong>
        @foreach($errors->all() as $error)
            <ul>
                <li> &nbsp;&nbsp;&nbsp;{{ $error }}</li>
            </ul>
        @endforeach
    </div>
@endif
