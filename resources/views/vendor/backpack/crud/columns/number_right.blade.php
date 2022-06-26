{{-- regular object attribute --}}
<p class="text-right" style="padding-right:70px;">{{ (array_key_exists('prefix', $column) ? $column['prefix'] : '').number_format($entry->{$column['name']}, array_key_exists('decimals', $column) ? $column['decimals'] : 0).(array_key_exists('suffix', $column) ? $column['suffix'] : '') }}</p>
