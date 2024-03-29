@extends('layouts.app')

@section('title', 'Suggestion')

@section('content')
    <div style="margin-top: 100px;">
        <div class="row justify-content-center shadow-sm rounded-3">
            <div class="col-4">
                <h4 class="text-center">Suggested</h4>

                <div class="row justify-content-center mt-3">
                    @foreach ($suggested_users as $user)
                        @if(Auth::user()->id != $user->id)
                            <div class="row justify-content-center mt-3">
                                {{-- icon --}}
                                <div class="col-3">
                                    <a href="{{ route('profile.show', $user->id) }}">
                                        @if ($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-md">
                                        @else
                                            <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                                        @endif
                                    </a>
                                </div>
                    
                                {{-- name + email + followers --}}
                                <div class="col-6">
                                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold small">{{ $user->name }}</a>

                                    <p class="text-muted  mb-0">{{ $user->email }}</p>

                                    <p>{{ $user->followers->count() }} {{ $user->followers->count() == 1 ? 'follower' : 'followers' }}</p>
                                </div>
                    
                                {{-- follow button --}}
                                <div class="col-3">
                                    <form action="{{ route('follow.store', $user->id) }}" method="post" class="d-line">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Follow</button>
                                    </form>    
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection