@extends('layouts.mobile', ['back' => $rota, 'showBack' => false, 'showOthers' => false])

@section('content')
    <div class="">
        @if (session('type') == 'student')
            <div class="card">
                {{-- <div class="card-body"> --}}
                {{-- <div class="table-responsive"> --}}
                <table class="table table-hover ">
                    <tbody>
                        @foreach ($disciplinas as $item)
                            <tr>
                                <td>
                                    <form action="{{ route('student.conteudos') }}">
                                        @csrf
                                        <input name="id" type="hidden" value="{{ $item->id }}" />
                                        <button type="submit" class="btn btn-warning">{{ $item->name }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- </div> --}}
                {{-- </div> --}}
            </div>
            @section('style')
                <link href="/css/mobile.css" rel="stylesheet">
            @endsection
        @endif
    </div>
@endsection

{{-- @section('page-name', $schools->name) --}}
