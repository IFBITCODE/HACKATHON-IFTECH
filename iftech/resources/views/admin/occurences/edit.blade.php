<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Editar ocorrência</title>

</head>

<body>

<div style="max-width: 800px; margin: 40px auto;">

    <h1>
        Editar ocorrência
    </h1>

    <form
        method="POST"
        action="{{ route(
            'admin.occurrences.update',
            $occurrence
        ) }}"
    >

        @csrf
        @method('PUT')

        <p>
            <label>Título</label><br>

            <input
                type="text"
                name="title"
                value="{{ old(
                    'title',
                    $occurrence->title
                ) }}"
                style="width:100%"
                required
            >
        </p>

        <p>
            <label>Descrição</label><br>

            <textarea
                name="description"
                rows="5"
                style="width:100%"
                required
            >{{ old(
                'description',
                $occurrence->description
            ) }}</textarea>
        </p>

        <p>
            <label>Local</label><br>

            <input
                type="text"
                name="location"
                value="{{ old(
                    'location',
                    $occurrence->location
                ) }}"
                style="width:100%"
                required
            >
        </p>

        <p>
            <label>Categoria</label><br>

            <input
                type="text"
                name="category"
                value="{{ old(
                    'category',
                    $occurrence->category
                ) }}"
                style="width:100%"
                required
            >
        </p>

        <p>
            <label>Gravidade</label><br>

            <select name="severity">

                @foreach([
                    'baixa' => 'Baixa',
                    'media' => 'Média',
                    'alta' => 'Alta',
                    'critica' => 'Crítica'
                ] as $value => $label)

                    <option
                        value="{{ $value }}"
                        @selected(
                            $occurrence->severity === $value
                        )
                    >
                        {{ $label }}
                    </option>

                @endforeach

            </select>

        </p>

        <p>
            <label>Status</label><br>

            <select name="status">

                @foreach([
                    'aberta' => 'Aberta',
                    'em_atendimento' => 'Em atendimento',
                    'resolvida' => 'Resolvida',
                    'cancelada' => 'Cancelada'
                ] as $value => $label)

                    <option
                        value="{{ $value }}"
                        @selected(
                            $occurrence->status === $value
                        )
                    >
                        {{ $label }}
                    </option>

                @endforeach

            </select>

        </p>

        <p>
            <label>Data da ocorrência</label><br>

            <input
                type="datetime-local"
                name="occurred_at"
                value="{{ $occurrence
                    ->occurred_at
                    ->format('Y-m-d\TH:i') }}"
                required
            >
        </p>

        <p>
            <label>Observações de resolução</label><br>

            <textarea
                name="resolution_notes"
                rows="4"
                style="width:100%"
            >{{ old(
                'resolution_notes',
                $occurrence->resolution_notes
            ) }}</textarea>
        </p>

        <button type="submit">
            Salvar alterações
        </button>

    </form>

</div>

</body>

</html>