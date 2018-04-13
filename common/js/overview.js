// github.com/nazar-pc/PickMeUp pickmeup dates function
addEventListener('DOMContentLoaded', function () {
	pickmeup('#datefrom', {
		position       : 'bottom',
    format         : 'a b d Y',
		first_day			 : 0,
    default_date   : false,
		hide_on_select : true
	});
});
