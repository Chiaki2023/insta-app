@extends('layouts.app')

@section('title', 'Following Users')

@section('content')
    {{-- include header here --}}
    @include('users.profile.header')

    {{-- show all following users here --}}
    <div style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-4">
                <h3 class="text-center text-secondary">Following</h3>

                @if ($user->followingID->isNotEmpty())
                    @foreach ($user->followingID as $following)
                    <div class="row justify-content-center mt-3">
                        {{-- icon --}}
                        <div class="col-3">
                            <a href="{{ route('profile.show', $following->followingUser->id) }}">
                                @if ($following->followingUser->avatar)
                                    <img src="{{ $following->followingUser->avatar }}" alt="{{ $following->followingUser->name }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>

                        {{-- name --}}
                        <div class="col-6">
                            <a href="{{ route('profile.show', $following->followingUser->id) }}" class="text-decoration-none text-dark fw-bold small">{{ $following->followingUser->name }}</a>
                        </div>

                        {{-- following / follow button --}}
                        <div class="col-3">
                            @if (Auth::user()->id != $following->followingUser->id)
                                @if ($following->followingUser->isFollowed())
                                    {{-- unfollow user --}}
                                    <form action="{{ route('follow.destroy', $following->followingUser->id) }}" method="post" class="d-line">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent p-0 text-secondary">Following</button>
                                    </form>
                                @else
                                    {{-- follow user --}}
                                    <form action="{{ route('follow.store', $following->followingUser->id) }}" method="post" class="d-line">
                                        @csrf
                                        <button type="submit" class="border-0 bg-transparent p-0 text-primary">Follow</button>
                                    </form>  
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach    
                @else
                    <h3 class="text-muted text-center">No Following Yet.</h3>
                @endif
            </div>
        </div>
    </div>
@endsection