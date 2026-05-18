@extends('layouts.app')

@php
    $pageName = __('Class subjects');
@endphp

@section('page-name', $pageName)
No student registered in this class
@section('content')
    <div class="card">
        <div class="card-body">
            @if (!empty($disciplinas))
                <div class="table-responsive">
                    <form method="POST" action="{{ route('turmas.storeDisciplinaProf') }}">
                        <table class="table table-hover table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('Subject') }}</th>
                                    <th>{{ __('Choose the teacher') }}
                                    </th>

                                </tr>
                            </thead>
                            <tbody>

                                @csrf
                                @foreach ($disciplinas as $disc)
                                    <tr>

                                        <td>{{ $disc->dname }}</td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" name="cbx_{{ $disc->did }}">
                                                    @foreach ($professores as $item)
                                                        <option value="{{ $item->id }}"
                                                            @if ($item->id === $disc->pid) selected="selected" @endif>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                    
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div>
                    <h2>{{ __('No model class registered') }}</h2>
                </div>
            @endif
        </div>
    </div>
@endsection
