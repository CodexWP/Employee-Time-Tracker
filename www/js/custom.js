/*
Template Name: Monster Admin
Author: Themedesigner
Email: niravjoshi87@gmail.com
File: js
*/
$(function() {
    "use strict";
    $(function() {
        $(".preloader").fadeOut();
    });
    jQuery(document).on('click', '.mega-dropdown', function(e) {
        e.stopPropagation()
    });
    // ============================================================== 
    // This is for the top header part and sidebar part
    // ==============================================================  
    var set = function() {
        var width = (window.innerWidth > 0) ? window.innerWidth : this.screen.width;
        var topOffset = 70;
        if (width < 500) {
            $("body").addClass("mini-sidebar");
            $('.navbar-brand span').hide();
            $(".scroll-sidebar, .slimScrollDiv").css("overflow-x", "visible").parent().css("overflow", "visible");
            $(".sidebartoggler i").addClass("ti-menu");
        } else {
            $("body").removeClass("mini-sidebar");
            $('.navbar-brand span').show();
            $(".sidebartoggler i").removeClass("ti-menu");
        }

        var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $(".page-wrapper").css("min-height", (height) + "px");
        }

    };
    $(window).ready(set);
    $(window).on("resize", set);

    // topbar stickey on scroll

    $(".fix-header .topbar").stick_in_parent({

    });

    // this is for close icon when navigation open in mobile view
    $(".nav-toggler").click(function() {
        $("body").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
        $(".nav-toggler i").addClass("ti-close");
    });
    $(".sidebartoggler").on('click', function() {
        $(".sidebartoggler i").toggleClass("ti-menu");
    });

    // ============================================================== 
    // Auto select left navbar
    // ============================================================== 
    $(function() {
        var url = window.location;
        var element = $('ul#sidebarnav a').filter(function() {
            return this.href == url;
        }).addClass('active').parent().addClass('active');
        while (true) {
            if (element.is('li')) {
                element = element.parent().addClass('in').parent().addClass('active');
            } else {
                break;
            }
        }
    });

    // ============================================================== 
    // Sidebarmenu
    // ============================================================== 
    $(function() {
        $('#sidebarnav').metisMenu();
    });
    // ============================================================== 
    // Slimscrollbars
    // ============================================================== 
    $('.scroll-sidebar').slimScroll({
        position: 'left',
        size: "5px",
        height: '100%',
        color: '#dcdcdc'
    });

    // ============================================================== 
    // Resize all elements
    // ============================================================== 
    $("body").trigger("resize");

    var quill_sidebar_task = new Quill('#sidebar_newtask_editor', {
        theme: 'snow',
        placeholder: 'Enter task details...'
    });
    var ajaxurl = $("meta[name='ajax']").attr("url");

    $("#add-task-menu").click(function(){
        $.ajax({
            url: ajaxurl + "?op=getprojectlist",
            type: 'get',
            processData: false,
            contentType: false,
            dataType:'json',
            beforeSend:function() {
                var html = '<option value="" selected>Project Loading....</option>';
                $("#sidebar-add-task-modal").find("select[name='sidebar-project-list']").html(html);
            },
            success:function(resp){

                if(resp.status=='success')
                {
                    var data = resp.data;
                    var html = '<option value="" selected>Choose Project</option>';
                    for(var i=0;i<data.length;i++)
                    {
                        html = html + '<option value="'+data[i].id+'" selected>'+data[i].name+'</option>';
                    }
                    $("#sidebar-add-task-modal").find("select[name='sidebar-project-list']").html(html);
                }
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Error - ' + errorMessage);
            }
        })
    })


    $("form#sidebar-add-task-form").submit(function(e) {

        e.preventDefault();
        var desc = JSON.stringify(quill_sidebar_task.getContents());
        var title = $("input[name='sidebar-add-task-title']").val();
        var pid = $("select[name='sidebar-project-list']").val();
        if(title=='' || pid==''){alert('Title/Project field is empty.');return;}

        var fd = new FormData();
        fd.append('task_content', desc);
        fd.append('task_title',title);
        fd.append('project_id', pid);

        $.ajax({
            url: ajaxurl + "?op=addprojecttask",
            data: fd,
            type: 'post',
            processData: false,
            contentType: false,
            dataType:'json',
            headers : {'X-CSRF-Token': $(this).find("input[name='_csrfToken']").val()},
            beforeSend:function(){
                $("form#sidebar-add-task-form").find("input[type='submit']").attr('disabled','disabled');
                $("form#sidebar-add-task-form").find(".processing-complete").hide();
                $("form#sidebar-add-task-form").find(".processing-spin").show();
            },
            success: function ( resp ) {
                if(resp.status=='success')
                {
                    $("input[name='sidebar-add-task-title']").val('')
                    quill_sidebar_task.setContents('');
                }
                $("form#sidebar-add-task-form").find(".processing-spin").hide();
                $("form#sidebar-add-task-form").find("input[type='submit']").removeAttr('disabled');
                $("form#sidebar-add-task-form").find(".processing-complete").show();
            },
            error: function(xhr, status, error){
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                alert('Error - ' + errorMessage);
                $("form#sidebar-add-task-form").find(".processing-spin").hide();
                $("form#sidebar-add-task-form").find("input[type='submit']").removeAttr('disabled');
            }
        });
    })


});

function showstatus(status,message){
    if(status=='success')
    {
        status = 'Success';
        var type = 'pastel-info';
    }
    else
    {
        status = 'Failed';
        var type = 'pastel-danger';
    }
    $.notify({
        title: status,
        message: message
    },{
        type: type,
    });
}


