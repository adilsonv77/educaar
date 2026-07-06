@extends('layouts.app')
 <!-- #region-->
@section('page-name', $titulo)

@section('style')
    <style>
        #selectPanel{
            border: 1px solid #b3b3b3;
            font-size: 14px;
            width: 130px;
            padding: 2px;
            height: fit-content;
            margin: 6px 12px;
            margin-bottom: 23px;
            background-image: linear-gradient(#e9e9e9,#d9d9d9);
        } 
        #selectPanel:hover{
            background-color: #a7f2fe;
            background-image: none;
            border: 1px solid #319dd7;
        }
        .custom-switch .custom-control-label::before {
            border-width: 1.2px;
        }
        .custom-switch.switch .custom-control-label::after {
            border-width: 1.2px;
            top: 25%!important;
        }
        .custom-control {
            line-height: 1.5em!important;
        }

        /* Por algum motivo margin-top vem com 0.25rem, desabilitei pois causa desalinhamento com o resto da linha */
        .form-text {
            margin-top: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('content.store') }}"  autocomplete="off">
                    @csrf
                    <input name="id" type="hidden" value="{{ $id }}" />
                    <input name="acao" type="hidden" value="{{ $acao }}" />

                    <div class="form-group row">
                        <label for="name">{{ __('Name') }}* </label>
                    
                        <input id="name" type="text" maxlength="100" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $name) }}" required 
                            placeholder=
                                @if($name !== "") $name 
                                @else "{{ __('Name') }}"
                                @endif
                        autofocus />
                    </div>
                    @if (session('type') == 'teacher')
                    <div class="form-group row">
                        <label for="">{{ __('Subject') }}*</label>
                        <select class="form-control" name="disciplina_id">
                            @foreach ($disciplinas as $item)
                                <option value="{{ $item->tid }}_{{ $item->did }}"
                                    @if ($item->tid."_".$item->did === $disciplina) selected="selected" @endif>
                                    {{ $item->tnome }} - {{ $item->dnome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <div class="custom-control custom-switch switch">
                            <input type="hidden" name="ordered" value="0">
                            <input type="checkbox" name="ordered" id="ordered" class="custom-control-input" value="1" @if($sort_activities >= 1) checked @endif>
                            <label for="ordered" class="custom-control-label">{{ __('Ordered Content') }}</label>
                        </div>
                    </div>

                    <div class="extras collapse" id="extras">
                        <div class="mb-4">
                            <div class="custom-control custom-switch switch">
                                <input type="hidden" name="random" value="0">
                                <input type="checkbox" class="custom-control-input" name="random" id="random" value="1" @if($sort_activities == 2) checked @endif>
                                <label for="random" class="custom-control-label">{{ __('Randomly Ordered Content') }}</label>
                                <div class="form-text alert-danger d-inline-block small ml-1 p-0" id="randomAlert" role="alert">{{ __('With this option, each student will have a different order in which to answer the activities.') }}</div>
                            </div>
                        </div>
                    </div>

                    @endif
                          
                    @if (session('type') == 'admin')
                    <livewire:discporturma :turma="$turma" :disciplinaKey="$disciplina"/>
                    @endif

                    <div class="form-group row mt-4">
                        <input type="submit" value="{{ __('Save') }}" class="btn btn-success">
                    </div>




                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = {
                ordered: document.getElementById('ordered'),
                random: document.getElementById('random'),
                camposExtras: document.getElementById('extras'),
                rndAlert: document.getElementById('randomAlert')
            }
            
            function updateUI() {
                if(el.ordered.checked) {
                    el.random.disabled = false;
                    $(el.camposExtras).collapse('show');
                } else {
                    el.random.disabled = true;
                    $(el.camposExtras).collapse('hide');
                }
            }
            
            el.ordered.addEventListener('change', updateUI);
            updateUI();
        });
    </script>
@endsection