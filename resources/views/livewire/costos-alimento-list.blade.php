<div class="p-6">
    <h3 class="text-xl font-semibold mb-4">Codigo del alimento: {{ $alimento_id }}</h3>

    @if ($costos->isEmpty())
        <p>No hay costos registrados para este alimento.</p>
    @else
        @php
            $precios = $costos->pluck('precio');
            $total = $costos->count();
            $min = $precios->min();
            $max = $precios->max();
            $promedio = $precios->avg();

            $varianza = $precios->reduce(function($carry, $item) use ($promedio) {
                return $carry + pow($item - $promedio, 2);
            }, 0) / $total;

            $desviacion = sqrt($varianza);
        @endphp

        <!-- Tabla de costos -->
        <table class="w-full border-collapse mb-6">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Precio</th>
                    <th class="px-4 py-2 text-left">Unidad de medida</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Creado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($costos->sortByDesc('created_at') as $costo)
                    <tr>
                        <td class="px-4 py-2">${{ number_format($costo->precio, 2) }}</td>
                        <td class="px-4 py-2">{{ strtoupper($costo->unidad_medida) }}</td>
                        <td class="px-4 py-2">{{ ucfirst($costo->estado) }}</td>
                        <td class="px-4 py-2">{{ $costo->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Resumen aplicado en texto -->
        <p>
            Para el alimento con Codigo <strong>{{ $alimento_id }}</strong> se han registrado <strong>{{ $total }}</strong> costos distintos.
            Los precios varían entre <strong>${{ number_format($min, 2) }}</strong> y <strong>${{ number_format($max, 2) }}</strong>,
            con un promedio de <strong>${{ number_format($promedio, 2) }}</strong>.
            La desviación estándar de los precios es de <strong>${{ number_format($desviacion, 2) }}</strong>, lo que indica
            la variabilidad de los costos registrados.
        </p>
    @endif
</div>
