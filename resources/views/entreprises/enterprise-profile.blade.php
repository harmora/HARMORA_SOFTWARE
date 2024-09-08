@extends('layout')

@section('title')
    {{ get_label('enterprise_profile', 'Enterprise Profile') }}
@endsection

@section('content')
<style>
    .card-gradient {
        background: linear-gradient(135deg, #228dd5 50%, #ffffff 50%);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
    }

    .card-gradient::before {
        content: '';
        position: absolute;
        top: -20px;
        left: -20px;
        right: -20px;
        bottom: -20px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        filter: blur(30px);
        z-index: -1;
    }

    .profile-pic-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        border: 8px solid #fff;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        background: #228dd5;
        transition: transform 0.4s ease;
    }

    .profile-pic-container:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .profile-pic {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .admin-dashboard-card {
        width: 550px;
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .admin-dashboard-card:hover {
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        transform: translateY(-10px);
    }

    .admin-dashboard-title {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 15px;
        color: #228dd5;
    }

    .admin-dashboard-description-container {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        width: 100%;
    }

    .admin-dashboard-description {
        max-width: 450px;
        width: 100%;
        color: #333;
        overflow-wrap: break-word;
        white-space: pre-wrap;
    }

    .input-container {
        position: relative;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        width: 10px;
        height: 10px;
        background-color: #228dd5;
        border-radius: 50%;
    }

    .input-with-icon {
        padding-left: 30px;
        border: 1px solid #228dd5;
        border-radius: 5px;
    }

    .input-with-icon:focus {
        border-color: #228dd5;
        box-shadow: 0 0 0 0.2rem rgba(221, 223, 226, 0.25);
    }
</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">{{ get_label('dashboard', 'Dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('enterprise.profile') }}">{{ get_label('enterprise_profile', 'Enterprise Profile') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ auth()->user()->entreprise->denomination }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12 col-md-12 col-12 mb-4">
            <div class="card-gradient text-center">
                <div class="card-body" style="display: flex; justify-content:center;">
                    <div class="admin-dashboard-card">
                        <div class="profile-pic-container">
                            <img src="{{ auth()->user()->entreprise->photo ? asset('storage/' . auth()->user()->entreprise->photo) : asset('storage/photos/no-image.jpg') }}" alt="Profile Picture" class="profile-pic">
                        </div>
                        <h2 class="admin-dashboard-title">{{auth()->user()->entreprise->denomination}}</h2>
                        <div class="admin-dashboard-description-container">
                            <p class="admin-dashboard-description">{{auth()->user()->entreprise->address." - ".auth()->user()->entreprise->city}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{ get_label('enterprise_information', 'Enterprise Information') }}</h4>
                    <div class="row">
                        @foreach([
                            'denomination' => 'Denomination',
                            'Forme_juridique' => 'Legal Form',
                            'Identifiant_commun_entreprise' => 'ICE',
                            'REGISTRE_COMMERCE' => 'IC',
                            'IDENTIFIANT_FISCALE' => 'IF',
                            'address' => 'Address',
                            'city' => 'City',
                            'state' => 'State',
                            'country' => 'Country'
                        ] as $key => $label)
                        <div class="mb-3 col-md-6">
                            <label class="form-label">{{ get_label($key, $label) }}</label>
                            <div class="input-container">
                                <div class="input-icon"></div>
                                <input class="form-control input-with-icon" value="{{ auth()->user()->entreprise->$key ?? '--' }}" readonly>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
