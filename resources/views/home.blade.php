@extends('voyager::master')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 400px;
            margin-top: 15px;
        }

        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
@stop
@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                          class="form-edit-add"
                          action="#"
                          method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                    {{ method_field("PUT") }}

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
                                $dataTypeRows = $dataType->{('editRows')};
                            @endphp

                            @foreach($dataTypeRows as $row)
                            <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($call->{$row->field.'_'.('edit')}) {
                                        $call->{$row->field} = $call->{$row->field.'_'.('edit')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}"
                                            style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif
                                <div
                                    class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $row->field == "call_belongsto_urgency_off_call_relationship" ? 4 : ($display_options->width ?? 12) }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                    {{ $row->slugify }}
                                    <label class="control-label" for="name">{{ $row->display_name }}</label>
                                    @if($row->field == 'user_id')
                                        <input type="number" value="{{ Auth::user()->id }}" disabled
                                               class="form-control">
                                    @elseif($row->field == 'call_address')
                                        <input type="text" class="form-control" name="call_address"
                                               placeholder="Адреса виклику"
                                               value="{{ $call->call_address }}">
                                        <div id="map"></div>

                                            <button type="button" class="btn btn-primary show-hospitals">Показати
                                                лікарні поруч з пацієнтом
                                            </button>
                                        <button type="button" class="btn btn-primary show-marshrut">Прокласти маршрут
                                        </button>
                                    @else
                                        @include('voyager::multilingual.input-hidden-bread-edit-add')
                                        @if (isset($row->details->view))
                                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $call, 'content' => $call->{$row->field}, 'action' => 'edit'])
                                        @elseif ($row->type == 'relationship')
                                            @include('voyager::formfields.relationship', ['options' => $row->details])
                                        @else
                                            {!! app('voyager')->formField($row, $dataType, $call) !!}
                                        @endif

                                        @foreach (app('voyager')->afterFormFields($row, $dataType, $call) as $after)
                                            {!! $after->handle($row, $dataType, $call) !!}
                                        @endforeach
                                        @if ($errors->has($row->field))
                                            @foreach ($errors->get($row->field) as $error)
                                                <span class="help-block">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                            <div class="patient col-md-12">
                                <h3>Бригада</h3>
                                {{--@if($call->brigade_call->first())--}}
                                <div class="form-group col-md-2">
                                    <label>Номер бригади</label>
                                    <input type="number" name="brigade_id" value="{{ $brigade_call->brigade_id }}"
                                           disabled class="form-control">
                                    <input type="hidden" name="brigade_id" value="{{ $brigade_call->brigade_id }}"
                                           class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Статус бригади</label>
                                    <select name="brigade_call[brigade_status_id]" class="select2 form-control">
                                        <option value="{{ null }}">Виберіть статус</option>
                                        @foreach(\App\Models\BrigadeStatus::all() as $status)
                                            <option
                                                value="{{ $status->id }}" {{ $status->id == $brigade_call->brigade_status_id ? 'selected' : '' }}>
                                                {{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Час прибуття до пацієнта</label>
                                    <input type="datetime-local" name="brigade_call[arrival_time]"
                                           value="{{ $brigade_call->arrival_time }}" class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Час виїзду до пацієнта</label>
                                    <input type="datetime-local" name="brigade_call[departure_time]"
                                           value="{{ $brigade_call->departure_time }}" class="form-control">
                                </div>
                                {{--@endif--}}
                            </div>
                            <div class="patient form-group">
                                <h3>Пацієнт</h3>
                                <div class="form-group  col-md-4 ">
                                    <label class="control-label" for="patient[name]">ПІБ пацієнта</label>
                                    <input type="text" value="{{ $patient->name }}" name="patient[name]"
                                           class="form-control">
                                </div>
                                <div class="form-group  col-md-3 ">
                                    <label class="control-label" for="patient[phone]">Тел</label>
                                    <input type="text" value="{{ $patient->phone }}" name="patient[phone]"
                                           class="form-control">
                                </div>
                                <div class="form-group  col-md-1 ">
                                    <label class="control-label" for="patient[age]">Вік</label>
                                    <input type="number" name="patient[age]" value="{{ $patient->age }}" min="0"
                                           max="130" class="form-control">
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
                                    <input type="text" name="patient[address]" value="{{ $patient->address }}"
                                           class="form-control">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[social_status_id]">Соц. статус</label>
                                    <select name="patient[social_status_id]" class="select2">
                                        <option value="{{ null }}"> Вибрати</option>
                                        @foreach(\App\Models\SocialStatus::all() as $status)
                                            <option
                                                value="{{ $status->id }}" {{ $patient->social_status_id == $status->id ? 'checked' : '' }}>
                                                {{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[further_stay]">Подальше
                                        перебування</label>
                                    <input type="text" name="patient[further_stay]" value="{{ $patient->further_stay }}"
                                           class="form-control">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[departure_type]">Тип виїзду</label>
                                    <input type="text" name="patient[departure_type]"
                                           value="{{ $patient->departure_type }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[place_off_call]">Місце виклику</label>
                                    <input type="text" name="patient[place_off_call]"
                                           value="{{ $patient->place_off_call }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[call_type]">Виклик</label>
                                    <input type="text" name="patient[call_type]" value="{{ $patient->call_type }}"
                                           class="form-control">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[trauma_id]">Травма</label>
                                    <select name="patient[trauma_id]" class="select2">
                                        <option value="{{ null }}"> Вибрати</option>
                                        @foreach(\App\Models\Trauma::all() as $trauma)
                                            <option
                                                value="{{ $trauma->id }}" {{ $patient->trauma_id == $trauma->id ? 'checked' : '' }}>
                                                {{ $trauma->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[pathological_date]">Початок патолог.
                                        стану</label>
                                    <input type="datetime-local" name="patient[pathological_date]"
                                           value="{{ $patient->pathological_date }}" class="form-control">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[result_id]">Результат</label>
                                    <select name="patient[result_id]" class="select2">
                                        <option value="{{ null }}"> Вибрати</option>
                                        @foreach(\App\Models\Result::all() as $result)
                                            <option
                                                value="{{ $result->id }}" {{ $patient->result_id == $result->id ? 'checked' : '' }}>
                                                {{ $result->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label"
                                           for="patient[unsuccessful_departure]">Безрезультатний</label>
                                    <input type="checkbox"
                                           name="patient[unsuccessful_departure]"
                                           class="toggleswitch"
                                           data-on="Так"
                                           {{ $patient->unsuccessful_departure ? ' checked' : '' }}
                                           data-off="Ні">
                                </div>
                                <div class="form-group  col-md-2 ">
                                    <label class="control-label" for="patient[previous_diagnosis]">Попередній
                                        діагноз</label>
                                    <select name="patient[previous_diagnosis]" class="select2">
                                        <option value="{{ null }}"> Вибрати</option>
                                        @foreach(json_decode(\App\Models\CallSetting::where('slug', \App\Models\CallSetting::DIAGNOSES_SLUG)->first()->value) as $key => $name)
                                            <option
                                                value="{{ $key }}" {{ $patient->previous_diagnosis == $key ? 'checked' : '' }}>
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  col-md-2">
                                    <label class="control-label" for="patient[anamnesis]">Анамнез</label>
                                    <input type="text" name="patient[anamnesis]" value="{{ $patient->anamnesis }}"
                                           class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <h4>Об'єктивні дані</h4>
                                    @foreach($patient->objective_data as $key => $value)
                                        <div class="col-md-2">
                                            <label>{{ $key }}</label>
                                            <input type="text" name="patient[objective_data][{{ $key }}]"
                                                   value="{{ $value }}" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group col-md-12">
                                    <h4>Надана медична допомога</h4>
                                    @foreach($patient->medicaid as $key => $value)
                                        <div class="col-md-2">
                                            <label>{{ $key }}</label>
                                            <input type="text" name="patient[medicaid][{{ $key }}]" value="{{ $value }}"
                                                   class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group col-md-12">
                                    <h4>Стан після надання допомоги</h4>
                                    @foreach($patient->state_after_relief as $key => $value)
                                        <div class="col-md-2">
                                            <label>{{ $key }}</label>
                                            <input type="text" name="patient[state_after_relief][{{ $key }}]"
                                                   value="{{ $value }}" class="form-control">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <button type="submit"
                                    class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
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
@endsection
@section('javascript')
    <script type="text/javascript">
        var geocoder;
        var map;
        var markersArray = [];
            @if($dataTypeContent->call_address)
        var call_address = '{{ $dataTypeContent->call_address}}';
        var brigade_address = '{{ $brigade->address }}';
            @endif
            @if(isset($brigade))
        var brigades = {};
            brigades['{{ $brigade->car->state_number }}'] = '{{ $brigade->address }}';
        @endif
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            alert("Latitude: " + position.coords.latitude +
                "<br>Longitude: " + position.coords.longitude);
        }

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: {lat: 50.45466, lng: 30.5238}
            });
            geocoder = new google.maps.Geocoder();

            // var directionsDisplay = new google.maps.DirectionsRenderer();
            // var directionsService = new google.maps.DirectionsService;
            //
            // var request = {
            //     origin: new google.maps.LatLng(60.023539414725356,30.283663272857666), //точка старта
            //     destination: new google.maps.LatLng(59.79530896374892,30.410317182540894), //точка финиша
            //     travelMode: google.maps.DirectionsTravelMode.DRIVING //режим прокладки маршрута
            // };
            //
            // directionsService.route(request, function(response, status) {
            //     if (status == google.maps.DirectionsStatus.OK) {
            //         directionsDisplay.setDirections(response);
            //     }
            // });
            //
            // directionsDisplay.setMap(map);

            if (typeof call_address != "undefined") {
                codeAddress(call_address, 'Постраждалий', 'http://maps.google.com/mapfiles/kml/shapes/man.png', true);
            }

            if (typeof brigades != "undefined") {
                $.each(brigades, function (car_number, brigade_address) {
                    codeAddress(brigade_address, car_number, 'http://maps.google.com/mapfiles/kml/pal3/icon46.png', false);
                })
            }
        }

        function codeAddress(address, title, image = 'http://maps.google.com/mapfiles/kml/shapes/man.png', push_marker = true) {
            geocoder.geocode({'address': address}, function (results, status) {
                if (status === 'OK') {
                    createMarker(results[0].geometry.location, title, image, push_marker)
                } else {
                    alert('Google не може знайти адресу');
                }
            });
        }

        function createMarker(position, title, image, push_marker) {
            var marker = new google.maps.Marker({
                map: map,
                position: position,
                title: title,
                icon: image,
                animation: google.maps.Animation.DROP
            });
            if (push_marker) {
                markersArray.push(marker);
            }
        }

        function clearOverlays() {
            for (var i = 0; i < markersArray.length; i++) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }

        $('.show-marshrut').on('click', function () {
            // clearOverlays();

            var directionsDisplay = new google.maps.DirectionsRenderer();
            var directionsService = new google.maps.DirectionsService;

            var request = {
                // origin: new google.maps.LatLng(60.023539414725356,30.283663272857666), //точка старта
                // destination: new google.maps.LatLng(59.79530896374892,30.410317182540894), //точка финиша
                //  color: '#f30d0d',
                travelMode: google.maps.DirectionsTravelMode.DRIVING //режим прокладки маршрута
            };

            geocoder.geocode({'address': call_address}, function (results, status) {
                request['origin'] =  results[0].geometry.location;
            });
            geocoder.geocode({'address': brigade_address}, function (results, status) {
                request['destination'] =  results[0].geometry.location;

                if (status == 'OK') {
                    directionsService.route(request, function(response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            directionsDisplay.setDirections(response);
                        }
                    });
                    directionsDisplay.setMap(map);
                }
            });
        });

        $('input[name=call_address]').on('focusout', function () {
            clearOverlays();
            var call_address = $(this).val();
            codeAddress(call_address, 'Постраждалий');
            $('.show-hospitals').show();
        });

        $('.show-hospitals').on('click', function () {
            call_address = $('input[name=call_address]').val();
            showNearbyHospitals(call_address);
        });

        function showNearbyHospitals(address) {
            geocoder.geocode({'address': address}, function (results, status) {
                if (status === 'OK') {
                    var location = results[0].geometry.location;
                    var request = {
                        location: location,
                        radius: '1000',
                        type: ['hospital']
                    };

                    var service = new google.maps.places.PlacesService(map);
                    service.nearbySearch(request, function (results, status) {
                        if (status == google.maps.places.PlacesServiceStatus.OK) {
                            for (var i = 0; i < results.length; i++) {
                                var place = results[i];
                                console.log(place, place.html_attributions);
                                createMarker(place.geometry.location, place.name, '', true);
                            }
                        }
                    })
                }
                ;
            })
        }

        $(document).ready(function () {
            $('.navbar.navbar-default.navbar-fixed-top.navbar-top').remove();
            $('.side-menu.sidebar-inverse').remove();


        });
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API_KEY', 'AIzaSyD7WhpaC4avnAxj8hBFaMOasjhtV7BU8MQ') }}&callback=initMap&libraries=places"
        async defer></script>
@stop
