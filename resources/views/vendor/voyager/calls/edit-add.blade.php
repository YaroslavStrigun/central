@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                          class="form-edit-add"
                          action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                          method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                    @if($edit)
                        {{ method_field("PUT") }}
                    @endif

                    <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                            @foreach($dataTypeRows as $row)
                            <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif
                                <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $row->field == "call_belongsto_urgency_off_call_relationship" ? 4 : ($display_options->width ?? 12) }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ $row->display_name }}</label>
                                    @if($row->field == 'user_id')
                                        <input type="number" value="{{ Auth::user()->id }}" disabled class="form-control">
                                    @else
                                    @include('voyager::multilingual.input-hidden-bread-edit-add')
                                    @if (isset($row->details->view))
                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add')])
                                    @elseif ($row->type == 'relationship')
                                        @include('voyager::formfields.relationship', ['options' => $row->details])
                                    @else
                                        {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                    @endif

                                    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                    @endforeach
                                    @if ($errors->has($row->field))
                                        @foreach ($errors->get($row->field) as $error)
                                            <span class="help-block">{{ $error }}</span>
                                        @endforeach
                                    @endif
                                    @endif
                                </div>
                            @endforeach

                            <div class="patient">
                                <h3>Пацієнт</h3>
                                @php
                                    $patient = $dataTypeContent->patient ?? new \App\Patient();
                                @endphp
                                <div class="form-group  col-md-4 ">
                                    <label class="control-label" for="patient[name]">ПІБ пацієнта</label>
                                    <input type="text" value="{{ $patient->name }}" name="patient[name]" class="form-control">
                                </div>
                                <div class="form-group  col-md-3 ">
                                    <label class="control-label" for="patient[phone]">Тел</label>
                                    <input type="text" value="{{ $patient->phone }}" name="patient[phone]" class="form-control">
                                </div>
                                <div class="form-group  col-md-1 ">
                                    <label class="control-label" for="patient[age]">Вік</label>
                                    <input type="number" name="patient[age]" value="{{ $patient->age }}" min="0" max="130" class="form-control">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[male]">Стать</label>
                                    <input type="checkbox"
                                           name="patient[male]"
                                           class="toggleswitch"
                                           data-on="Чоловіча"
                                           {{ $patient->male ? ' checked' : '' }}
                                           data-off="Жіноча">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[reasonableness]">Обґрунтованість</label>
                                    <input type="checkbox"
                                           name="patient[reasonableness]"
                                           class="toggleswitch"
                                           data-on="Проф"
                                           {{ $patient->reasonableness ? ' checked' : '' }}
                                           data-off="Непроф">
                                </div>
                                <div class="form-group  col-md-4 ">
                                    <label class="control-label" for="patient[address]">Адресса</label>
                                    <input type="text" name="patient[address]" value="{{ $patient->address }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[social_status_id]">Соц. статус</label>
                                    <select name="patient[social_status_id]" class="select2">
                                        <option> Вибрати </option>
                                        @foreach(\App\Models\SocialStatus::all() as $status)
                                            <option value="{{ $status->id }}" {{ $patient->social_status_id == $status->id ? 'checked' : '' }}>
                                                {{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[further_stay]">Подальше перебування</label>
                                    <input type="text" name="patient[further_stay]" value="{{ $patient->further_stay }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[departure_type]">Тип виїзду</label>
                                    <input type="text" name="patient[departure_type]" value="{{ $patient->departure_type }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[place_off_call]">Місце виклику</label>
                                    <input type="text" name="patient[place_off_call]" value="{{ $patient->place_off_call }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[call_type]">Виклик</label>
                                    <input type="text" name="patient[call_type]" value="{{ $patient->call_type }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[trauma_id]">Травма</label>
                                    <select name="patient[trauma_id]" class="select2">
                                        <option> Вибрати </option>
                                        @foreach(\App\Models\Trauma::all() as $trauma)
                                            <option value="{{ $trauma->id }}" {{ $patient->trauma_id == $trauma->id ? 'checked' : '' }}>
                                                {{ $trauma->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[pathological_date]">Початок патолог. стану</label>
                                    <input type="datetime-local" name="patient[pathological_date]" value="{{ $patient->pathological_date }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[result_id]">Результат</label>
                                    <select name="patient[result_id]" class="select2">
                                        <option> Вибрати </option>
                                        @foreach(\App\Models\Result::all() as $result)
                                            <option value="{{ $result->id }}" {{ $patient->result_id == $result->id ? 'checked' : '' }}>
                                                {{ $result->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[unsuccessful_departure]">Безрезультатний</label>
                                    <input type="checkbox"
                                           name="patient[unsuccessful_departure]"
                                           class="toggleswitch"
                                           data-on="Так"
                                           {{ $patient->unsuccessful_departure ? ' checked' : '' }}
                                           data-off="Ні">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[previous_diagnosis]">Попередній діагноз</label>
                                    <select name="patient[previous_diagnosis]" class="select2">
                                        <option> Вибрати </option>
                                        @foreach([] as $diagnosis)
                                            <option value="{{ $diagnosis->id }}" {{ $patient->previous_diagnosis == $diagnosis->id ? 'checked' : '' }}>
                                                {{ $diagnosis->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[anamnesis]">Анамнез</label>
                                    <input type="text" name="patient[anamnesis]" value="{{ $patient->anamnesis }}" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <h4>Об'єктивні дані</h4>
                                </div>
                                <div class="form-group col-md-12">
                                    <h4>Надана медична допомога</h4>
                                </div>
                                <div class="form-group col-md-12">
                                    <h4>Стан після надання допомоги</h4>
                                </div>
                            </div>

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                          enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                               onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
            return function() {
                $file = $(this).siblings(tag);

                params = {
                    slug:   '{{ $dataType->slug }}',
                    filename:  $file.data('file-name'),
                    id:     $file.data('id'),
                    field:  $file.parent().data('field-name'),
                    multi: isMulti,
                    _token: '{{ csrf_token() }}'
                }

                $('.confirm_delete_name').text(params.filename);
                $('#confirm_delete_modal').modal('show');
            };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.type != 'date' || elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
            $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
