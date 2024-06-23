@extends('layouts.app')

@php
    $pageName =  $content->name . " (".$type.")";
@endphp

@section('page-name', $pageName)

@section('content')

    <div class="card">
        <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($results as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                </tr>
                            @endforeach

                         </tbody>
                    </table>

                </div>
        </div>
    </div>
@endsection
