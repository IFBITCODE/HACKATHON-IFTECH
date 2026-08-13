<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>{{ $occurrence->title }}</title>

</head>

<body>

<div style="max-width: 900px; margin: 40px auto;">

    <h1>
        {{ $occurrence->title }}
    </h1>

    <p>
        <strong>Descrição:</strong><br>
        {{ $occurrence->description }}
    </p>

    <p>
        <strong>Local:</strong>
        {{ $occurrence->location }}
    </p>

    <p>
        <strong>Categoria:</strong>
        {{ $occurrence->category }}
    </p>

    <p>
        <strong>Gravidade:</strong>
        {{ ucfirst($occurrence->severity) }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ str_replace(
            '_',
            ' ',
            ucfirst($occurrence->status)
        ) }}
    </p>

    <p>
        <strong>Ocorrida em:</strong>
        {{ $occurrence->occurred_at->format(
            'd/m/Y H:i'
        ) }}
    </p>

    @if($occurrence->resolved_at)

        <p>
            <strong>Resolvida em:</strong>
            {{ $occurrence->resolved_at->format(
                'd/m/Y H:i'
            ) }}
        </p>

    @endif

    @if($occurrence->resolution_notes)

        <p>
            <strong>Observações:</strong><br>

            {{ $occurrence->resolution_notes }}
        </p>

    @endif

    <br>

    <a
        href="{{ route(
            'admin.occurrences.edit',
            $occurrence
        ) }}"
    >
        Editar
    </a>

    |

    <a href="{{ route('admin.occurrences.index') }}">
        Voltar
    </a>

</div>

</body>

</html>