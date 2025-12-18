<?php
// if we got $disabled from parent templates, we setup disabled for chield templates

    $action = 'Edit_';

    $modal_title = 'Kunde info bearbeiten';


$modal_id = 'KundeInfoEdit_'.$contact->id;
?>

<div class="modal fade" id="{{$modal_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <?php $object_id = str_replace(".", "", basename(__FILE__)).$modal_id; ?>
    <div class="modal-dialog modal-lg" style="max-width: 70%; top: 25%; ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$modal_title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                {!! Form::open(['url' => action([\App\Http\Controllers\ContactController::class, 'saveContactInfo'], $contact->id ), 'method' => 'post']) !!}
                {{-- <form id="quickForm" action="{{ route('contact_info_update', [$contact->id]) }}" method="POST">
                    @csrf
                    @method('PUT') --}}
                <input type="hidden" name="contact_id" value="{{$contact->id}}" id="contact_id">
                
                    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('contract', __('contract.contract_infos') . ':') !!}
                            {!! Form::textarea('contract_info', $contact->info_description, ['class' => 'form-control ', 'id' => 'basic-example']); !!}
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        @lang('messages.save')
                    </button>
                     <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                        @lang('messages.close')
                    </button>               
                </div>
                {{-- </form> --}}
                 {!! Form::close() !!}
            </div>
            <!-- /.modal-body -->
        </div>
    </div>
</div>

