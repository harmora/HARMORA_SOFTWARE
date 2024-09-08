@extends('layout')

@section('title')
    {{ get_label('enterprise_profile', 'Enterprise Profile') }}
@endsection

@section('content')
<style>
    .card-half-blue-half-white {
    background: linear-gradient(135deg, #228dd5 50%, #ffffff 50%);
    color: #fff;
    border: none;
}

.profile-pic {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border: 5px solid #fff;
}

.admin-dashboard-card {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.admin-dashboard-card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    transform: translateY(-5px);
}

.admin-dashboard-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.admin-dashboard-description-container {
    display: flex;
    justify-content: center; /* Centers the content horizontally */
    align-items: center; /* Centers the content vertically, if needed */
    text-align: center; /* Centers the text within the paragraph */
    width: 100%; /* Ensures the container takes the full width */
}


.admin-dashboard-description {
    max-width: 400px; /* Adjust this value as needed */
    width: 100%; /* Make sure the paragraph takes the full width of its container */
    overflow-wrap: break-word; /* Break long words onto the next line if necessary */
    white-space: pre-wrap; /* Preserve spaces and line breaks */
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
    background-color: #228dd5; /* Blue accent color */
    border-radius: 50%;
}

.input-with-icon {
    padding-left: 30px; /* Adjust padding to make space for the icon */
    border: 1px solid #228dd5; /* Blue border for inputs */
    border-radius: 5px; /* Rounded corners */
}

.input-with-icon:focus {
    border-color: #228dd5; /* Darker blue on focus */
    box-shadow: 0 0 0 0.2rem rgba(221, 223, 226, 0.25); /* Blue shadow on focus */
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
                        <a  href="{{ route('enterprise.profile') }}">{{ get_label('enterprise_profile', 'Enterprise Profile') }}</a>
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
            <div class="card card-half-blue-half-white text-center">
                <div class="card-body">


                    <div class="admin-dashboard-card ">
                        <a href="{{ route('enterprise.profile') }}" class="d-block mb-3">
                            <img src="{{ auth()->user()->entreprise->photo ? asset('storage/' . auth()->user()->entreprise->photo) : asset('storage/photos/no-image.jpg') }}" alt="Profile Picture" class="profile-pic rounded-circle shadow-lg">
                        </a>
                        <h2 class="admin-dashboard-title" style="color: #228dd5">{{auth()->user()->entreprise->denomination}}</h2>

                        <div class="admin-dashboard-description-container">

                        <p class="admin-dashboard-description text-dark">{{auth()->user()->entreprise->address." - ".auth()->user()->entreprise->city}}</p>
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
