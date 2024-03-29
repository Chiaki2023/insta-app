@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <style>
        .col-4{
            overflow-y: scroll;
        }

        .card-body{
            position: absolute;
            top: 65px;
        }
    </style>

    <div class="row border shadow">
        {{-- post image --}}
        <div class="col p-0 border-end">
            <img src="{{ $post->image }}" alt="post id{{ $post->id }}" class="w-100">
        </div>

        {{-- details of a post --}}
        <div class="col-4 px-0 bg-white">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        {{-- avatar --}}
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $post->user_id) }}">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
                
                        {{-- name --}}
                        <div class="col ps-0">
                            <a href="{{ route('profile.show', $post->user_id) }}" class="text-decoration-none text-dark">
                                {{ $post->user->name }}
                            </a>
                        </div>
                
                        {{-- Action Buttons --}}
                        <div class="col-auto">
                            {{-- If you are the owner of the post, you can edit or delete this post --}}
                            @if (Auth::user()->id === $post->user->id)
                                <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>

                                <div class="dropdown-menu">
                                    {{-- edit --}}
                                    <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                                        <i class="fa-regular fa-pen-to-square"></i> Edit
                                    </a>
                                    {{-- delete --}}
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{ $post->id }}">
                                        <i class="fa-regular fa-trash-can"></i> Delete
                                    </button>
                                </div>
                                {{-- Include modal here --}}
                                @include('users.posts.contents.modals.delete')
                            @else
                                @if ($post->user->isFollowed())
                                    {{-- unfollow user --}}
                                    <form action="{{ route('follow.destroy', $post->user->id) }}" method="post" class="d-line">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent p-0 text-secondary">Following</button>
                                    </form>
                                @else
                                    {{-- follow user --}}
                                    <form action="{{ route('follow.store', $post->user_id) }}" method="post" class="d-line">
                                        @csrf
                                        <button type="submit" class="border-0 bg-transparent p-0 text-primary">Follow</button>
                                    </form>  
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body w-100 bg-white">
                    {{-- heart button + No. of Likes + categories --}}
                    <div class="row align-item-center">
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
                            <span>{{ $post->likes->count() }}</span>
                        </div>

                        {{-- categories --}}
                        <div class="col text-end">
                            @forelse ($post->categoryPost as $category_post)
                                <div class="badge bg-secondary bg-opacity-50">
                                    {{ $category_post->category->name }}
                                </div>
                            @empty
                                <div class="badge bg-dark text-wrap">Uncategorized</bg-dark>
                            @endforelse
                        </div>
                    </div>

                    {{-- owner + description --}}
                    <a href="{{ route('profile.show', $post->user_id) }}" class="text-decoration-none text-dark fw-bold">
                        {{ $post->user->name }}
                    </a> 
                    &nbsp;
                    <p class="fw-light d-inline">{{ $post->description }}</p>
                    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

                    {{-- form comment --}}
                    <div class="mt-4">
                        {{-- Show all comments of the post here --}}
                        <form action="{{ route('comment.store', $post->id) }}" method="post">
                            @csrf
                    
                            <div class="input-group">
                                <textarea name="comment_body{{ $post->id }}" rows="1" placeholder="Add a comment..." class="form-control form-control-sm">{{ old('comment_body' . $post->id) }}</textarea>
                    
                                <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
                            </div>
                            {{-- Error --}}
                            @error('comment_body' . $post->id)
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </form>

                        {{-- Show all comments --}}
                        @if ($post->comments->isNotEmpty())
                            <ul class="list-group mt-2">
                                @foreach ($post->comments as $comment)
                                    <li class="list-group-item border-0 p-0 mb-2 bg-white">
                                        <a href="{{ route('profile.show', $comment->user_id) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                                        &nbsp;
                                        <p class="d-inline fw-light">{{ $comment->body }}</p>

                                        <form action="{{ route('comment.destroy', $comment->id ) }}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            <span class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>

                                            {{-- If the Auth user is the OWNER of the comment, show details button. --}}
                                            @if (Auth::user()->id === $comment->user->id)
                                            &middot;
                                            <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                                            @endif
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection