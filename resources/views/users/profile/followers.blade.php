@extends('layouts.app')

@section('title', 'Followers')

@section('content')
    {{-- include header here --}}
    @include('users.profile.header')

    {{-- show all followers here --}}
    <div style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-4">
                <h3 class="text-center text-secondary">Followers</h3>

                @if ($user->followers->isNotEmpty())
                    @foreach ($user->followers as $follower)
                        <div class="row justify-content-center mt-3">
                            {{-- icon --}}
                            <div class="col-3">
                                <a href="{{ route('profile.show', $follower->follower->id) }}">
                                    @if ($follower->follower->avatar)
                                        <img src="{{ $follower->follower->avatar }}" alt="{{ $follower->follower->name }}" class="rounded-circle avatar-sm">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                    @endif
                                </a>
                            </div>
                
                            {{-- name --}}
                            <div class="col-6">
                                <a href="{{ route('profile.show', $follower->follower->id) }}" class="text-decoration-none text-dark fw-bold small">{{ $follower->follower->name }}</a>
                            </div>
                
                            {{-- following / follow button --}}
                            <div class="col-3">
                                @if (Auth::user()->id != $follower->follower->id)
                                    @if ($follower->follower->isFollowed())
                                        {{-- unfollow user --}}
                                        <form action="{{ route('follow.destroy', $follower->follower->id) }}" method="post" class="d-line">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="border-0 bg-transparent p-0 text-secondary">Following</button>
                                        </form>
                                    @else
                                        {{-- follow user --}}
                                        <form action="{{ route('follow.store', $follower->follower->id) }}" method="post" class="d-line">
                                            @csrf
                                            <button type="submit" class="border-0 bg-transparent p-0 text-primary">Follow</button>
                                        </form>  
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="text-muted text-center">No Followers Yet.</h3>
                @endif
            </div>
        </div>
    </div>
@endsection