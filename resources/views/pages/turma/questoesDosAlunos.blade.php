@extends('layouts.app')

@php
    $pageName = 'Alunos';
@endphp

@section('page-name', $pageName)

@section('content')

<div id="jorje">
    <form action="{{ route('student.results') }}>
         
    </form>

    <br>
  </div>

    <div class="card">
        <div class="card-body">
           
                <div class="table-responsive">
                    <table class="table table-hover table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Mial</th>
                                <th>Alal</th>
                                <th>Pir</th>
                                <th>Berro</th>
                            </tr>
                        </thead>
                        <tbody>

                           
                        </tbody>
                    </table>

                 </div>
         
                <div>
                    <h2>Nenhum aluno</h2>
                </div>
            
        </div>
    </div>



@endsection