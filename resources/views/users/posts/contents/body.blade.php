{{-- clickable image --}}
<div class="container p-0">
    <a href="{{ route('post.show', $post->id) }}">
        <img src="{{ $post->image }}" alt="post id{{ $post->id }}" class="w-100">
    </a>
</div>

<div class="card-body">
    {{-- heart button + No. of Likes + categories --}}
    <div class="row align-items-center">
        {{-- heart button --}}
        <div class="col-auto">
            @if ($post->isLiked())
                {{-- unlike post --}}
                <form action="{{ route('like.destroy', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm shadow-none p-0">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </button>
                </form>
            @else
                {{-- like post --}}
                <form action="{{ route('like.store', $post->id) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-sm shadow-none p-0">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </form>
            @endif
        </div>

        {{-- No. of Likes --}}
        <div class="col-auto px-0">
            <span data-bs-toggle="modal" data-bs-target="#like-user-{{ $post->id }}">
                {{ $post->likes->count() }}
            </span>
        </div>
        {{-- Include modal here --}}
        @include('users.posts.contents.modals.like')

        {{-- categories --}}
        <div class="col text-end">
            @forelse ($post->categoryPost as $category_post)
                <div class="badge bg-secondary bg-opacity-50">
                    {{ $category_post->category->name }}
                </div>
            @empty
                <div class="badge bg-dark text-wrap">Uncategorized</div>
            @endforelse 
        </div>
    </div>

    {{-- owner + description --}}
    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">
        {{ $post->user->name }}
    </a> 
    &nbsp;
    <p class="fw-light d-inline">{{ $post->description }}</p>
    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

    {{-- include comment form here --}}
    @include('users.posts.contents.comments')
</div>