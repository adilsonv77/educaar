<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Atividades do Professor</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h3 {
            margin-top: 30px;
        }

        .atividade {
            page-break-inside: avoid;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding-left: 15px;
        }

        hr {
            margin: 20px 0;
        }
    </style>
</head>

<body>

    <img src="{{ public_path('images/LOGO_HORIZONTAL.png') }}" alt="Logo" style="display: block; margin: 0 auto;" width="200">

    <h2>Relatório de Atividades</h2>
    <p><strong>Professor ID:</strong> {{ Auth::id() }}</p>
    <p><strong>Conteúdo:</strong> {{ $conteudo->name }}</p>

    @foreach($atividades as $index => $atividade)
    <div class="atividade">
        <h3>Atividade {{ $index + 1 }}: {{ $atividade->name }}</h3>
        <p><strong>Criada em:</strong> {{ \Carbon\Carbon::parse($atividade->created_at)->format('d/m/Y') }}</p>

        {{-- Imagem da atividade, se existir --}}
        @if($atividade->marcador)
        <p><strong>Imagem relacionada:</strong></p>
        <img src="{{ public_path('marcadores/' . $atividade->marcador) }}" width="200" alt="Imagem da atividade">
        @else
        <p><em>Sem imagem relacionada.</em></p>
        @endif

        {{-- Questões da atividade --}}
        @forelse($atividade->questions as $qIndex => $questao)
        <p><strong>Questão {{ $qIndex + 1 }}:</strong> {{ $questao->titulo ?? $questao->question ?? 'Sem texto' }}</p>
        <ul>
            <li><strong>A)</strong> {{ $questao->a }}</li>
            <li><strong>B)</strong> {{ $questao->b }}</li>
            <li><strong>C)</strong> {{ $questao->c }}</li>
            <li><strong>D)</strong> {{ $questao->d }}</li>
        </ul>
        @empty
        <p><em>Nenhuma questão cadastrada para esta atividade.</em></p>
        @endforelse
    </div>
    <hr>
    @endforeach

</body>

</html>
