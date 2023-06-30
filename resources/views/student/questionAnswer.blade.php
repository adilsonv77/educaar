@extends('layouts.mobile')

@section('page-name')
@section('content')
    <div class="">
        @can('student')
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <tbody>
                                @foreach ($activities as $item)
                                    <div>
                                        <form action="{{ route('student.questoes') }}">
                                            @csrf
                                            <input name="id" type="hidden" value="{{ $item->id }}" />
                                            <button type="submit" class="btn btn-warning">{{ $item->name }}</button>
                                        </form>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @section('script')
                <script type="module" src="/js/app.js"></script>
            @endsection
        @endcan
    </div>
    {{-- <div class="form-check">
        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
        <label class="form-check-label" for="flexRadioDefault1">
            Default radio
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
        <label class="form-check-label" for="flexRadioDefault2">
            Default checked radio
        </label>
    </div> --}}
@endsection
