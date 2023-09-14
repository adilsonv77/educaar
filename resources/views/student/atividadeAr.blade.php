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
        <table class="table table-hover table-responsive-sm">
        <tbody>
            <tr>
                <td>
                    <button type="submit" @if ($respondida) hidden="hidden" @endif class="btn btn-success">Salvar</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('student.store') }}">
                    @csrf
                        <input type="hidden" name="return" value="1">
                        <button type="submit" class="btn btn-warning">Retornar</button>  
                    </form>
                </td>  
            </tr>
        </tbody>
        </table>

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

@section('script')
    <script>
        history.forward();
    </script>

@endsection

@endsection
