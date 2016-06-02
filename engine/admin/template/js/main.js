var lang; // Use lang.key (example lang.admin_area)
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
$("#show_left").sideNav();
$("#show_right").sideNav({
     menuWidth: 300,
     edge: 'right',
     closeOnClick: true
   }
 );
$('select').material_select();
(function($){
  $(window).load(function(){

    $("#material_tabs").mCustomScrollbar({
      axis:"x",
      advanced:{autoExpandHorizontalScroll:true},
      callbacks:{
        onOverflowY:function(){
          var opt=$(this).data("mCS").opt;
          if(opt.mouseWheel.axis!=="y") opt.mouseWheel.axis="y";
        },
        onOverflowX:function(){
          var opt=$(this).data("mCS").opt;
          if(opt.mouseWheel.axis!=="x") opt.mouseWheel.axis="x";
        },
      }
    });

  });
})(jQuery);
$("#updateb").on("click", function(){
  $(".new_version").html('<div><i class="mi spin">settings</i> '+lang.upexec+'</div>');
  $.ajax({
    url: "",
  	type: 'POST',
  	data: {"action":true,"AJAX" : true},
  	async: false,
  	cache: false,
  	success: function(html)
  	{
  		json = $.parseJSON(html);
      if(json.success)
      {
        $(".new_version").html('<div>'+lang.updsuccess+'</div>');
        $("#verison").html(json.version).parent(".warning").removeClass("warning");
      }
      else
      {
        $(".new_version").html('<div>'+lang.upderror+'</div>');
      }
  	}
  });
});

$("body").on("click", "#nextlogin", function()
{
	var params = "";
	if($("#email").val() != "")
	{
		value = $("#email").val();
		params = {"email" : value, "two_steps" : true, "AJAX" : true};
	}
	else if($("#login").val() != "")
	{
		value = $("#login").val();
		params = {"login" : value, "two_steps" : true, "AJAX" : true};
	}
	if($("#email").val() == "" || $("#login").val() == "")
	{
		$("#errors").html("Email or login empty");
		return;
	}
	$.ajax({
	  url: "",
	  type: 'POST',
		data: params,
	  async: false,
	  cache: false,
		success: function(html)
		{
			json = $.parseJSON(html);
			if(json.success)
			{
		  	$(".inps").removeClass("swap").addClass("swap");
				$("#photo").attr("src", json.photo);
				$("#name").html(json.name);
				$("#email_addr").html(json.email);
				$("#nextlogin").replaceWith('<a id="sigin">'+lang.signin+'</a>');
			  $("#backbtn").replaceWith('<a id="fs">'+lang.enteranother+'</a>');
				$("#errors").html("");
			}
			else
			{
				$("#errors").html(json.reason);
			}
		}
	});
});

$("body").on("click", "#sigin", function()
{
	$("#send").click();
});

$("body").on("click", "#fs", function()
{
	$(".inps").removeClass("swap");
	$("#name").html("");
	$("#email_addr").html("");
	$("#photo").attr("src","/engine/admin/template/images/noimage.jpg");
	$("#sigin").replaceWith('<a id="nextlogin">'+lang.next+'</a>');
	$("#fs").replaceWith('<a href="/" id="backbtn">'+lang.show_front+'</a>');
	$("#errors").html("");
});
tinymce.init({
  selector: '#editor',
  height: "200px",
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    '//www.tinymce.com/css/codepen.min.css'
  ]
});
$("#remove").on("click",function(event) {
	if(!confirm(lang.delete_it))
	{
		event.preventDefault();
	}
});
$('.clps').collapsible({
	accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
});
$("body").on("click", "#jsave", function()
{
	var subm = $(this);
	var fData = new FormData();
	var form = $("#jform");
	var d = "";
	var st = "";
	$.each( form[0], function( key, value ){
		st = "";
		d = $(this)[0];
		if(d.name != "" && d.type != "checkbox")
		{
    	fData.append(d.name, d.value);
		}
		if(d.type == "checkbox")
		{
			if(d.checked == true) st = true
			fData.append(d.name, st);
		}
  });
	if(form.attr("files"))
	{
		var inp = $('input[type="file"]');
		if(inp[0].files[0])
		{
			var files = inp.files[0];
			fData.append("file", files);
		}
	}
	$.ajax({
	  url: "",
	  type: 'POST',
		data: fData,
	  async: false,
	  cache: false,
    processData: false,
    contentType: false,
		beforeSend: function()
		{
			subm.addClass("disabled");
		},
		success: function(html)
		{
			subm.removeClass("disabled");
			json = $.parseJSON(html);
			if(json.success)
			{
				Materialize.toast(lang.saved, 3000);
			}
			else
			{
				subm.removeAttr("disabled");
				Materialize.toast(lang.saveerr+": "+json.reason, 3000);
			}
		}
	});
});
function installExt()
{
	var subm = $(this);
	var fData = new FormData();
	var files = $("#md_input")[0].files[0];
	fData.append("file", files);
	fData.append('action', 'install_new');
	fData.append('AJAX', true);
	$.ajax({
	  url: "",
	  type: 'POST',
		data: fData,
	  async: false,
	  cache: false,
    processData: false,
    contentType: false,
		success: function(html)
		{
			json = $.parseJSON(html);
			if(json.success)
			{
				Materialize.toast(lang.installed, 3000);
			}
			else
			{
				subm.removeAttr("disabled");
				Materialize.toast(lang.proc_error+": "+json.reason, 3000);
			}
		}
	});
};
function removeExt(id)
{
	$.ajax({
	  url: "",
	  type: 'POST',
		data: {"id":id,"action":"remove_md","AJAX":true},
	  async: false,
	  cache: false,
		success: function(html)
		{
			json = $.parseJSON(html);
			if(json.success)
			{
				Materialize.toast(lang.removed, 3000);
			}
			else
			{
				Materialize.toast(lang.proc_error+": "+json.reason, 3000);
			}
		}
	});
};
