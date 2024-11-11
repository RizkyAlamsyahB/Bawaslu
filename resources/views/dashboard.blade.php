@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <!-- User Profile Image -->
                    <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1 mb-3" width="100">

                    <!-- User Information -->
                    <h4 class="card-title">{{ Auth::user()->name }}</h4>
                    <p class="text-muted">{{ ucfirst(Auth::user()->role) }}</p>

                    <div class="row">
                        <div class="col-6 text-right">
                            <p><strong>Username:</strong></p>
                            <p><strong>Phone:</strong></p>
                            <p><strong>Joined:</strong></p>
                        </div>
                        <div class="col-6 text-left">
                            <p>{{ Auth::user()->username }}</p>
                            <p>{{ Auth::user()->phone }}</p>
                            <p>{{ Auth::user()->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Optional Association Information -->
                    @if (Auth::user()->role == 'kecamatan' && Auth::user()->kecamatan)
                        <p><strong>Kecamatan:</strong> {{ Auth::user()->kecamatan->name }}</p>
                    @elseif (Auth::user()->role == 'kelurahan' && Auth::user()->kelurahan)
                        <p><strong>Kelurahan:</strong> {{ Auth::user()->kelurahan->name }}</p>
                    @elseif (Auth::user()->role == 'tps' && Auth::user()->tps)
                        <p><strong>TPS:</strong> {{ Auth::user()->tps->name }}</p>
                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection
