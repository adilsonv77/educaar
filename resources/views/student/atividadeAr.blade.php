@extends('layouts.mobile')

@section('page-name')
@section('content')


    <form action="{{ route('student.store') }}">
        @foreach ($questions as $item)
            @csrf
            <div class="">
                <div class="card-body">
                    <div>
                        <input name="id" type="hidden" value="{{ $item->id }}" />
                        <input name="id" type="hidden" value="{{ $item->id }}" />
                        <h2 type="submit" style=" font-size: 25px" class="text">
                            {{ $loop->iteration }}.{{ $item->question }}
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach ($item->options as $option)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="questao{{ $item->id }}"
                                    id="flexRadioDefault{{ $loop->index }} {{ $item->id }}"
                                    value="{{ $loop->index }}">
                                <label for="flexRadioDefault{{ $loop->index }} {{ $item->id }}"
                                    class="form-check-label" style="font-size: 17px">
                                    {{ $option }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
        @endforeach
        <div class="form-group mt-4 d-flex justify-content-center mb-4">
            <input type="submit" value="Salvar" class="btn btn-success" id="btn_save">
        </div>
    </form>
@section('style')
    <style>
        input[type="radio"] {
            border: 0px;
            width: 7%;
            height: 2em;
        }

        .form-check-label {

            font-family: arial;

        }
    </style>
@endsection
@endsection
