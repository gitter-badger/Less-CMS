var lang; // Use lang.key (ex lang.admin_area)
$.ajax({
	url: "",
	type: 'POST',
	data: {"language":"getList", "AJAX" : true},
	async: false,
	cache: false,
	success: function(html)
	{
		lang = $.parseJSON(html);
	}
});
$(".menu_list").mCustomScrollbar(
{
  axis: "y",
  theme: "minimal-dark"
});
$('.member_menu').dropdown();
$('.file_seclect').tooltip();
$("body").on("click","#action", function(e)
{
  e.preventDefault();
  var action = $(this).attr("data-action");
  if(action == "ban")
  {
    if(window.confirm(lang.doban))
    {
      window.location = $(this).attr("href");
    }
  }
  else if(action == "unban")
  {
    if(window.confirm(lang.dounban))
    {
      window.location = $(this).attr("href");
    }
  }
  return;
});
$("body").on("click","#remove_md", function ()
{
	var id = $(this).attr("data-id");
	if(window.confirm(lang.sure_remove))
	{
		$.ajax({
			url: "",
			type: 'POST',
			data: {"id":id, "action":"remove_md", "AJAX" : true},
			async: false,
			cache: false,
			success: function(html)
			{
				window.location.reload();
			}
		});
	}

})
