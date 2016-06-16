Group by
<select id="resultGroup" name="group">
	<option value="daily" {!! isset($group) && $group == 'daily' ? 'selected="selected"' : '' !!}>Date</option>
	<option value="weekly" {!! isset($group) && $group == 'weekly' ? 'selected="selected"' : '' !!}>Week</option>
	<option value="monthly" {!! isset($group) && $group == 'monthly' ? 'selected="selected"' : '' !!}>Month</option>
</select>
