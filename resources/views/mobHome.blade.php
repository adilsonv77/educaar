@extends('layouts.mobile')

@section('content')
    <div class="">
        @can('student')
            <div class="card">
                {{-- <div class="card-body"> --}}
                <div class="table-responsive">
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
                </div>
                {{-- </div> --}}
            </div>
            @section('script')
                <script type="module" src="/js/app.js"></script>
            @endsection
        @endcan
    </div>
@endsection

{{-- @section('page-name', $schools->name) --}}
