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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>

    <img src="{{ public_path('images/LOGO_HORIZONTAL.png') }}" alt="Logo" style="display: block; margin: 0 auto;" width="200">


    <h2>Relatório de Atividades</h2>
    <p><strong>Professor ID:</strong> {{ Auth::id() }}</p>
    <p><strong>Ano Letivo:</strong> {{ $atividades->first()->ano_id ?? 'N/A' }}</p>
    <p><strong>Conteudo:</strong> {{ $conteudo->name }}</p>

    <table>

        <tbody>
            @foreach($atividades as $index => $atividade)
            <h3>Atividade {{ $index + 1 }}: {{ $atividade->name }}</h3>
            <p><strong>Criada em:</strong> {{ \Carbon\Carbon::parse($atividade->created_at)->format('d/m/Y') }}</p>

            @forelse($atividade->questions as $qIndex => $questao)
            <p><strong>Questão {{ $qIndex + 1 }}:</strong> {{ $questao->titulo ?? $questao->question ?? 'Sem texto' }}</p>

            <ul style="list-style-type: none; padding-left: 15px;">
                <li><strong>A)</strong> {{ $questao->a }}</li>
                <li><strong>B)</strong> {{ $questao->b }}</li>
                <li><strong>C)</strong> {{ $questao->c }}</li>
                <li><strong>D)</strong> {{ $questao->d }}</li>
            </ul>
            @empty
            <p><em>Nenhuma questão cadastrada para esta atividade.</em></p>
            @endforelse

            <hr>
            @endforeach



        </tbody>
    </table>

</body>

</html>