@extends('layouts.app')

@section('title', 'Explore People')

@section('content')
@auth
@if (request()->is('admin/*'))
    <ul class="navbar-nav me-auto border mb-3">
        <form action="{{ route('search') }}" style="width: 300px;">
        <input type="search" name="search" placeholder="Search..." value="{{ old('search') }}" class="form-control form-control-sm"></form>
    </ul>
@endif
@endauth

    <div class="row justify-content-center">
        <div class="col-5">
            <p class="h5 text-muted mb-4">Search results for "<span class="fw-bold ">{{ $search }}</span>"</p>

            @forelse ($users as $user)
                <div class="row align-items-center mb-3">
                    {{-- avatar --}}
                    <div class="col-auto">
                        <a href="{{ route('profile.show', $user->id) }}">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-md">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                            @endif
                        </a>
                    </div>

                    {{-- name + email --}}
                    <div class="col ps-0 text-truncate">
                        <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $user->name }}</a>
                        <p class="text-muted mb-8">{{ $user->email }}</p>
                    </div>

                    {{-- button follow/following --}}
                    <div class="col-auto">
                        @if (Auth::user()->id != $user->id)
                            @if ($user->isFollowed())
                                {{-- unfollow user --}}
                                <form action="{{ route('follow.destroy', $user->id) }}" method="post" class="d-line">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border-0 bg-transparent p-0 text-secondary">Following</button>
                                </form>
                            @else
                                {{-- follow user --}}
                                <form action="{{ route('follow.store', $user->id) }}" method="post" class="d-line">
                                    @csrf
                                    <button type="submit" class="border-0 bg-transparent p-0 text-primary">Follow</button>
                                </form>  
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <p class="lead text-muted text-center">No Users found.</p>
            @endforelse
        </div>
    </div>
@endsection