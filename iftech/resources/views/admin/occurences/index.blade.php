<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Ocorrências</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1400px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .filters form {
            display: flex;
            gap: 12px;
            align-items: end;
        }

        input,
        select,
        button {
            height: 38px;
            padding: 0 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            cursor: pointer;
        }

        .primary {
            background: #111827;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            text-align: left;
            padding: 14px;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f9fafb;
        }

        .badge {
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        a {
            text-decoration: none;
            color: #2563eb;
        }

        .success {
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            margin-bottom: 20px;
            border-radius: 8px;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <div>
            <h1>Ocorrências</h1>

            <p>
                Registro e acompanhamento de ocorrências turísticas.
            </p>
        </div>

        <a
            class="primary"
            style="padding: 10px 15px; color: white;"
            href="{{ route('admin.occurrences.create') }}"
        >
            Nova ocorrência
        </a>

    </div>

    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif

    <div class="filters">

        <form method="GET">

            <div>
                <label>Local</label>

                <input
                    name="location"
                    value="{{ request('location') }}"
                    placeholder="Ex.: Centro"
                >
            </div>

            <div>
                <label>Categoria</label>

                <select name="category">

                    <option value="">
                        Todas
                    </option>

                    <option value="Infraestrutura">
                        Infraestrutura
                    </option>

                    <option value="Limpeza">
                        Limpeza
                    </option>

                    <option value="Acesso">
                        Acesso
                    </option>

                    <option value="Evento">
                        Evento
                    </option>

                </select>
            </div>

            <div>
                <label>Gravidade</label>

                <select name="severity">

                    <option value="">
                        Todas
                    </option>

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
            </div>

            <div>
                <label>Status</label>

                <select name="status">

                    <option value="">
                        Todos
                    </option>

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
            </div>

            <button type="submit">
                Filtrar
            </button>

        </form>

    </div>

    <table>

        <thead>

        <tr>

            <th>Ocorrência</th>
            <th>Local</th>
            <th>Categoria</th>
            <th>Gravidade</th>
            <th>Status</th>
            <th>Data</th>
            <th>Ações</th>

        </tr>

        </thead>

        <tbody>

        @forelse($occurrences as $occurrence)

            <tr>

                <td>
                    {{ $occurrence->title }}
                </td>

                <td>
                    {{ $occurrence->location }}
                </td>

                <td>
                    {{ $occurrence->category }}
                </td>

                <td>
                    {{ ucfirst($occurrence->severity) }}
                </td>

                <td>
                    {{ str_replace(
                        '_',
                        ' ',
                        ucfirst($occurrence->status)
                    ) }}
                </td>

                <td>
                    {{ $occurrence->occurred_at->format(
                        'd/m/Y H:i'
                    ) }}
                </td>

                <td>

                    <div class="actions">

                        <a
                            href="{{ route(
                                'admin.occurrences.show',
                                $occurrence
                            ) }}"
                        >
                            Ver
                        </a>

                        <a
                            href="{{ route(
                                'admin.occurrences.edit',
                                $occurrence
                            ) }}"
                        >
                            Editar
                        </a>

                        <form
                            method="POST"
                            action="{{ route(
                                'admin.occurrences.destroy',
                                $occurrence
                            ) }}"
                        >

                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                Excluir
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>
                <td colspan="7">
                    Nenhuma ocorrência encontrada.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

    <br>

    {{ $occurrences->links() }}

</div>

</body>

</html>