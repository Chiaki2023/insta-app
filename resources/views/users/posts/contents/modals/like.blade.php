<div class="modal fade" id="like-user-{{ $post->id }}">
    <div class="modal-dialog" style="width: 350px;">
        <div class="modal-content border-dark">
            <div class="modal-header border-dark d-flex justify-content-center">
                <div class="h5 modal-title">
                    <i class="fa-solid fa-heart text-danger"></i> Likes
                </div>
            </div>

            <div class="modal-body mx-auto">
                @foreach ($post->likes as $like)
                    <div class="row align-items-center">                        
                        {{-- icon --}}
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $like->user->id) }}">
                                @if ($like->user->avatar)
                                    <img src="{{ $like->user->avatar }}" alt="{{ $like->user->name }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
            
                        {{-- name --}}
                        <div class="col-auto text-truncate pt-1">
                            <a href="{{ route('profile.show', $like->user->id) }}" class="text-decoration-none text-dark fw-bold small">{{ $like->user->name }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>