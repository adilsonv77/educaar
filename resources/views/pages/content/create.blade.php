@extends('layouts.app')
 <!-- #region-->
@section('page-name', $titulo)

@section('content')
    <div class="">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('content.store') }}"  autocomplete="off">
                    @csrf
                    <input name="id" type="hidden" value="{{ $id }}" />
                    <input name="acao" type="hidden" value="{{ $acao }}" />

                    <div class="form-group row">
                        <label for="name">Nome* </label>
                    
                        <div class="col-md-6">
                            <input id="name" type="text" maxlength="100" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name', $name) }}" required
                                placeholder="Digite aqui o nome do conteúdo" autofocus />
                        </div>
                    </div>
                    @if (session('type') == 'teacher')
                    <div class="form-group row">
                        <label for="">Escolha a Disciplina*</label>
                        <select class="form-control" name="disciplina_id">
                            @foreach ($disciplinas as $item)
                                <option value="{{ $item->tid }}_{{ $item->did }}"
                                    @if ($item->tid."_".$item->did === $disciplina) selected="selected" @endif>
                                    {{ $item->tnome }} - {{ $item->dnome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row">
                        <label for="sort_activities">Ordenar atividades deste conteúdo</label>
                        <div class="col-md-6">
                            <input type="checkbox" name="sort_activities" id="sort_activities" value="1" @if (old('sort_activities', $sort_activities ?? false)) checked @endif>
                        </div>
                    </div>

                    @endif
                    {{-- Essa parte foi transferida para o Livewire content-activities-order  
                    @if(Route::CurrentRouteName() == 'content.edit')
                        @if(isset($id) && $content->sort_activities)
                            <div class="mt-4">
                                @livewire('content-activities-order', ['contentId' => $content->id])
                            </div>
                        @endif
                    @endif
                    --}}
                          
                    @if (session('type') == 'admin')
                    <livewire:discporturma :turma="$turma" :disciplinaKey="$disciplina"/>
                    @endif

                    <div class="form-group row mt-4">
                        <input type="submit" value="Salvar" class="btn btn-success">
                    </div>




                </form>
            </div>
        </div>
    </div>
@endsection