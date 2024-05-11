@extends('layouts.app')

@php
    $pageName = 'Desenvolvedores';
@endphp

@section('page-name', $pageName)

@section('content')

    <div class="card">
        <div class="card-body">
            @if (!empty($devs))
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Nome</th>
                            </tr>
                        </thead>
                        <tbody>
                    <form action="{{ route('dev.store') }}" method="POST">
                        @csrf
                            @foreach ($devs as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="dev{{ $item->id}}" value="{{$item->id}}"
                                        @if($item->selected_dev==1) checked @endif>
                                        <label class="form-check-label" for="dev{{ $item->id}}">{{ $item->name }}</label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            <button type="submit" class="btn btn-success">Salvar</button>
                    </form>
            </tbody>
            </table>

        </div>
    @else
        <div>
            <h2>Nenhum desenvolvedor </h2>
        </div>
        @endif
    </div>
    </div>
@endsection
