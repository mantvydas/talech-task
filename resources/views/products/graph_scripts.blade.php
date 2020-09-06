@push('scripts')
    <!-- Lightweight charts -->
    <script src="https://unpkg.com/lightweight-charts@3.1.3/dist/lightweight-charts.standalone.production.js"></script>
    <script>
        function createChart(type = 'price', chartElementId, data) {
            chartElement = document.getElementById(chartElementId);

            let priceFormat;
            if (type === 'price') {
                priceFormat = {
                    type: 'price'
                };
            } else {
                priceFormat = {
                    type: 'custom',
                    minMove: 1,
                    formatter: price => price.toFixed(0),
                };
            }

            var chart = LightweightCharts.createChart(chartElement, {
                height: 300,
                rightPriceScale: {
                    borderColor: 'rgba(197, 203, 206, 1)',
                },
                timeScale: {
                    borderColor: 'rgba(197, 203, 206, 1)',
                    timeVisible: true,
                },
                localization: {
                    locale: 'lt-LT',
                    dateFormat: 'yyyy-MM-dd',
                },
            });

            var areaSeries = chart.addAreaSeries({
                topColor: 'rgba(33, 150, 243, 0.56)',
                bottomColor: 'rgba(33, 150, 243, 0.04)',
                lineColor: 'rgba(33, 150, 243, 1)',
                lineWidth: 2,
                autoscaleInfoProvider: original => {
                    const res = original();
                    if (res.priceRange !== null && type === 'quantity') {
                        res.priceRange.minValue = 0;
                    }
                    return res;
                },
            });

            areaSeries.applyOptions({
                priceFormat: priceFormat,
            });

            chart.timeScale().fitContent();
            areaSeries.setData(data);

            return chart;
        }

        window.addEventListener('load', function () {
            const priceHistoryData = [];
            const quantityHistoryData = [];

            @php
                $lastHistoryRecord = null;
            @endphp
            @foreach ($product->history as $historyRecord)
                @if (!isset($lastHistoryRecord) || $lastHistoryRecord->price != $historyRecord->price)
                priceHistoryData.push(
                    {!! json_encode((object) ['time' => strtotime($historyRecord->created_at), 'value' => $historyRecord->price]) !!}
                );
                @endif

                @if (!isset($lastHistoryRecord) || $lastHistoryRecord->quantity != $historyRecord->quantity)
                quantityHistoryData.push(
                    {!! json_encode((object) ['time' => strtotime($historyRecord->created_at), 'value' => $historyRecord->quantity]) !!}
                );
                @endif

                @php
                    $lastHistoryRecord = $historyRecord;
                @endphp
            @endforeach

            const priceHistoryChart = createChart('price', 'price-history', priceHistoryData);
            let quantityHistoryChart;

            $( ".Tab_header_tabs button" ).on( "click", function(e) {
                e.preventDefault();
                $('.tabs .tab').hide();
                $('.Tab_header_tabs button').removeClass('btn-primary').addClass('btn-secondary');
                $(this).removeClass('btn-secondary');
                $(this).addClass('btn-primary');
                $('#' + $(this).data('tab')).show().trigger('resizeChart');
            });

            $( window ).resize(function() {
                $( ".tabs .tab" ).trigger('resizeChart');
            });

            $('.tabs .tab').on('resizeChart', function() {
                let height = $(this).width() > 700 ? 300 : 200;
                if ($(this).attr('id') === 'price-history') {
                    priceHistoryChart.resize($(this).width(), height);
                    priceHistoryChart.timeScale().fitContent();
                } else if ($(this).attr('id') === 'quantity-history') {
                    if (!$(this).html().trim()) {
                        quantityHistoryChart = createChart('quantity', 'quantity-history', quantityHistoryData);
                    }
                    quantityHistoryChart.resize($(this).width(), height);
                    quantityHistoryChart.timeScale().fitContent();
                }
            });
        });
    </script>
@endpush
