<form
    method="GET"
    action="{{ route('admin.dashboard') }}"
    class="period-filter"
>
    <div>
        <label for="period">
            Período
        </label>

        <select name="period" id="period">
            <option
                value="month"
                @selected(request('period', 'month') === 'month')
            >
                Mês
            </option>

            <option
                value="quarter"
                @selected(request('period') === 'quarter')
            >
                Trimestre
            </option>

            <option
                value="year"
                @selected(request('period') === 'year')
            >
                Ano
            </option>
        </select>
    </div>

    <div>
        <label for="date">
            Data de referência
        </label>

        <input
            type="date"
            name="date"
            id="date"
            value="{{ request('date', now()->format('Y-m-d')) }}"
        >
    </div>

    <button type="submit">
        Aplicar filtros
    </button>
</form>