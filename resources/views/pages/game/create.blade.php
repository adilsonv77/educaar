@extends('layouts.app')

@section('page-name', $titulo)

@section('content')

    <style>
        .form-inline {
            display: flex;
            justify-content: flex-start;
        }

        .form-inline label {

            margin-right: 10px;
        }
    </style>

    <div class="card">
        <div class="card-body">
            @if (!empty($contents))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr style="text-align: center;">
                                <th style="text-align: left;">{{ __('Name') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Model Class') }}</th>
                                <th>{{ __('Select') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($contents as $item)
                                
                                
                                <tr style="text-align: center;">

                                    <td style="text-align: left;">{{ $item->content_name }}</td>
                                    <td>{{ $item->disc_name }}</td>
                                    <td>{{ $item->turma_name }}</td>
                                    <td>
                                        <form action="{{ route('game.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="content_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-square"></i></button>
                                        </form>
                                    </td>
                                    
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $contents->links('vendor.pagination.bootstrap-4') }}
                    </div>

                </div>
            @else
                <div>
                    <h2>{{ __('No Content') }}</h2>
                </div>
            @endif
        </div>
    </div>

@endsection