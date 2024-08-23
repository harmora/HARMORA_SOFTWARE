@php
    $fileExtension2='';
    $fileExtension='';
@endphp
@extends('layout')
@section('title')
<?= get_label('edit_document', 'Edit Document') ?>
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/documents')}}"><?= get_label('documents', 'Documents') ?></a>
                    </li>
                    <li class="breadcrumb-item active">
                        <?= get_label('edit', 'Edit') ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ url('documents/update/'. $document->id) }}" method="POST" class="form-submit-event" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_url" value="/documents">
                
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="reference" class="form-label"><?= get_label('reference', 'Reference') ?></label>
                        <input class="form-control" type="text" id="reference" name="reference" placeholder="<?= get_label('please_enter_reference', 'Please enter reference') ?>" value="{{ old('reference', $document->reference) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="description" class="form-label"><?= get_label('description', 'Description') ?></label>
                        <textarea class="form-control" id="description" name="description" placeholder="<?= get_label('please_enter_description', 'Please enter description') ?>">{{ old('description', $document->description) }}</textarea>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="type" class="form-label"><?= get_label('type', 'Type') ?></label>
                        <input class="form-control" type="text" id="type" name="type" placeholder="<?= get_label('please_enter_type', 'Please enter type') ?>" value="{{ old('type', $document->type) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="user" class="form-label"><?= get_label('user', 'User') ?></label>
                        <input class="form-control" type="text" id="user" name="user" placeholder="<?= get_label('please_enter_user', 'Please enter user') ?>" value="{{ old('user', $document->user) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="total_amount" class="form-label"><?= get_label('total_amount', 'Total Amount') ?></label>
                        <input class="form-control" type="text" id="total_amount" name="total_amount" placeholder="<?= get_label('please_enter_total_amount', 'Please enter total amount') ?>" value="{{ old('total_amount', $document->total_amount) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="paid_amount" class="form-label"><?= get_label('paid_amount', 'Paid Amount') ?></label>
                        <input class="form-control" type="text" id="paid_amount" name="paid_amount" placeholder="<?= get_label('please_enter_paid_amount', 'Please enter paid amount') ?>" value="{{ old('paid_amount', $document->paid_amount) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="remaining_amount" class="form-label"><?= get_label('remaining_amount', 'Remaining Amount') ?></label>
                        <input class="form-control" type="text" id="remaining_amount" name="remaining_amount" placeholder="<?= get_label('please_enter_remaining_amount', 'Please enter remaining amount') ?>" value="{{ old('remaining_amount', $document->remaining_amount) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="from_to" class="form-label"><?= get_label('from_to', 'From/To') ?></label>
                        <input class="form-control" type="text" id="from_to" name="from_to" placeholder="<?= get_label('please_enter_from_to', 'Please enter from/to') ?>" value="{{ old('from_to', $document->from_to) }}">
                    </div>
                    
                    <div class="mb-3 col-md-6">
                        <label for="facture" class="form-label"><?= get_label('facture', 'Facture') ?></label>
                        <div class="d-flex align-items-start gap-4">
                            @if($document->facture)
                            @php
                                $fileExtension = pathinfo($document->facture, PATHINFO_EXTENSION);
                            @endphp
                             @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif','webp']))
                                <img src="{{$document->facture ? asset('storage/' . $document->facture) : asset('storage/photos/no-image.jpg')}}" alt="user-avatar" class="d-block rounded" height="130" width="130" id="uploadedAvatar" />
                            @elseif (in_array(strtolower($fileExtension), ['pdf']))
                                <embed src="{{ asset('storage/' . $document->facture) }}" type="application/pdf" height="130" width="130" style="overflow:auto;" />
                            @else
                                <p class="text-muted mt-2"><?= get_label('file_not_supported', 'File not supported.') ?></p>
                            @endif
                            @endif
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile03" name="upload">
                                </div>
                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png_pdf', 'Allowed JPG or PNG or PDF.') ?>{{$fileExtension}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="devis" class="form-label"><?= get_label('devis', 'Devis') ?></label>
                        <div class="d-flex align-items-start gap-4">
                            @if($document->devis)
                                @php
                                    $fileExtension2 = pathinfo($document->devis, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($fileExtension2), ['jpg', 'jpeg', 'png', 'gif','webp']))
                                    <img src="{{$document->devis? asset('storage/' . $document->devis) : asset('storage/photos/doc.png')}}" alt="user-avatar" class="d-block rounded" height="130" width="130" id="uploadedAvatar" />
                                @elseif (in_array(strtolower($fileExtension2), ['pdf']))
                                    <embed src="{{ asset('storage/' . $document->devis) }}" type="application/pdf" height="130" width="130" style="overflow:auto;" /> 
                                @else
                                    <p class="text-muted mt-2"><?= get_label('file_not_supported', 'File not supported.') ?></p>        
                                @endif
                            @endif
                            <div class="button-wrapper">
                                <div class="input-group d-flex">
                                    <input type="file" class="form-control" id="inputGroupFile04" name="upload1">
                                </div>
                                <p class="text-muted mt-2"><?= get_label('allowed_jpg_png_pdf', 'Allowed JPG or PNG or PDF .') ?>{{$fileExtension2}}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" id="submit_btn" class="btn btn-primary me-2"><?= get_label('update', 'Update') ?></button>
                    <a href="{{ url('/documents') }}" class="btn btn-outline-secondary"><?= get_label('cancel', 'Cancel') ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection