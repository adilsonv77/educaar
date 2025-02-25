@extends('layouts.app')

@section('page-name', "Listagem de painéis")

@section('script-head')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet"
        href="{{ asset('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css?v=' . filemtime(public_path('editor/dist/plugins/colors/ui/trumbowyg.colors.min.css'))) }}">
    <link rel="stylesheet"
        href="{{ asset('editor/dist/ui/trumbowyg.min.css?v=' . filemtime(public_path('editor/dist/ui/trumbowyg.min.css'))) }}">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .trumbowyg-editor[contenteditable=true]:empty::before {
            content: attr(placeholder);
            color: #999;
        }
    </style>
@endsection

@section('content')
    <div id="trumbowyg-demo" placeholder="Insira seu texto aqui"></div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
    <script
        src="{{ asset('editor/dist/trumbowyg.min.js?v=' . filemtime(public_path('editor/dist/trumbowyg.min.js'))) }}"></script>
    <script
        src="{{ asset('editor/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js?v=' . filemtime(public_path('editor/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js'))) }}"></script>
    <script
        src="{{ asset('editor/dist/plugins/colors/trumbowyg.colors.min.js?v=' . filemtime(public_path('editor/dist/plugins/colors/trumbowyg.colors.min.js'))) }}"></script>
    <script>
        $('#trumbowyg-demo').trumbowyg({
            btns: [
                ['undo', 'redo'], // Only supported in Blink browsers
                ['strong', 'em'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['fontfamily', 'formatting','foreColor']
            ],
            autogrow: false
        });
    </script>
@endsection