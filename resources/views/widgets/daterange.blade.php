<input class="{{ isset($dateRangeClass) ? $dateRangeClass : 'dateRange'}}" name="date_range" />
<input type="hidden" name="min_date" value="{{ isset($min_date) ? date('Y-m-d', $min_date) : '' }}" />
<input type="hidden" name="max_date" value="{{ isset($max_date) ? date('Y-m-d', $max_date) : '' }}" />
<input type="hidden" name="defaultDateRange" value="{{ isset($default_date_range) ? $default_date_range : '' }}" />