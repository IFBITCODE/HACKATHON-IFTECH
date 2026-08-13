<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Nova ocorrência</title>

</head>

<body>

<div style="max-width: 800px; margin: 40px auto;">

    <h1>Nova ocorrência</h1>

    <form
        method="POST"
        action="{{ route('admin.occurrences.store') }}"
    >

        @csrf

        <p>
            <label>Título</label><br>

            <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                style="width:100%"
                required
            >
        </p>

        <p>
            <label>Descrição</label><br>

            <textarea
                name="description"
                style="width:100%"
                rows="5"
                required
            >{{ old('description') }}</textarea>
        </p>

        <p>
            <label>Local</label><br>

            <input
                type="text"
                name="location"
                value="{{ old('location') }}"
                style="width:100%"
                required
            >
        </p>

        <p>
            <label>Categoria</label><br>

            <input
                type="text"
                name="category"
                value="{{ old('category') }}"
                style="width:100%"
                required
            >
        </p>

        <p>
            <label>Gravidade</label><br>

            <select name="severity">

                <option value="baixa">
                    Baixa
                </option>

                <option value="media">
                    Média
                </option>

                <option value="alta">
                    Alta
                </option>

                <option value="critica">
                    Crítica
                </option>

            </select>

        </p>

        <p>
            <label>Status</label><br>

            <select name="status">

                <option value="aberta">
                    Aberta
                </option>

                <option value="em_atendimento">
                    Em atendimento
                </option>

                <option value="resolvida">
                    Resolvida
                </option>

                <option value="cancelada">
                    Cancelada
                </option>

            </select>

        </p>

        <p>
            <label>Data da ocorrência</label><br>

            <input
                type="datetime-local"
                name="occurred_at"
                value="{{ old(
                    'occurred_at',
                    now()->format('Y-m-d\TH:i')
                ) }}"
                required
            >
        </p>

        <p>
            <label>Observações de resolução</label><br>

            <textarea
                name="resolution_notes"
                rows="4"
                style="width:100%"
            >{{ old('resolution_notes') }}</textarea>
        </p>

        <button type="submit">
            Registrar ocorrência
        </button>

        <a href="{{ route('admin.occurrences.index') }}">
            Cancelar
        </a>

    </form>

</div>

</body>

</html>