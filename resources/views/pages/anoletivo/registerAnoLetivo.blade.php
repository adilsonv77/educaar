@extends('layouts.app')

@section('page-name', $titulo)

@section('content')
    <div class="card">

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

           <form method="POST" action="{{ route('anoletivo.store') }}">
                @csrf
                <input name="id" type="hidden" value="{{$id}}"/>
                <input name="acao" type="hidden" value="{{$acao}}"/>
                
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }} : </label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name', $name) }}" required autocomplete="name" autofocus>

                    </div>
                </div>               


                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Salvar
                        </button>
                    </div>
                </div>

            </form>

        
        </div>
    </div>
@endsection
