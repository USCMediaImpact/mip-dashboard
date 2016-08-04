<input class="dateRange" name="date_range" />
<input type="hidden" name="min_date" value="{{ date('Y-m-d', $min_date) }}" />
<input type="hidden" name="max_date" value="{{ date('Y-m-d', $max_date) }}" />
<input type="hidden" name="defaultDateRange" value="{{$default_date_range}}" />