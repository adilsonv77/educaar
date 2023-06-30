@extends('layouts.app')

@php
$pageName = $titulo;
@endphp

@section('page-name', $pageName)

@section('content')
<div class="card">
     <div class="card-body">
        @if (!empty($discanoletivo))
            <div class="table-responsive">
                <table class="table table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>Disciplina</th>
                            <th>Professores</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($discanoletivo as $item)
                        <tr>
                            <td>
                            {{$item['name']}}
                            </td>
                            <td>
                             [
                            @foreach ($item['professores'] as $prof)
                            {{$prof}}
                            @endforeach
                            ]
                            </td>
                            <td>
                                    <form action="{{ route('profanoletivo.edit', $item['id']) }}">
                                        @csrf
                                        <input type="hidden" name="anoletivoid" value="{{$anoletivo->id}}"/>
                                        <button type="submit" class="btn btn-warning">Editar</button>
                                    </form>
                                </td>

                        </tr>
                        
                    @endforeach
 
                    </tbody>
                </table>


                {{-- <div class="d-flex justify-content-center">
                    {!! $discanoletivo->links('vendor.pagination.bootstrap-4') !!}
                </div> --}}
            </div>
        @else
            <div>
                <h2>Nenhuma disciplina encontrada para o ano letivo</h2>
            </div>
        @endif
    </div>
</div>
@endsection