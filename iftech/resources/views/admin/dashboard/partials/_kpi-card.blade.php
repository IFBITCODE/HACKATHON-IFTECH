<div class="kpi-card">
    <div class="kpi-header">
        <span class="kpi-title">
            {{ $title }}
        </span>

        <span class="kpi-icon">
            {{ $icon ?? '📊' }}
        </span>
    </div>

    <div class="kpi-value">
        {{ $value }}
    </div>

    @if(isset($variation))
        <div class="kpi-variation
            {{ $variation >= 0 ? 'positive' : 'negative' }}">

            {{ $variation >= 0 ? '↑' : '↓' }}

            {{ abs($variation) }}%

            <span>vs. período anterior</span>
        </div>
    @endif
</div>