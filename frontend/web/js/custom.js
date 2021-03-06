$( document ).ready(function() {

    (function() {
        /*[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
         new SelectFx(el);
         } );*/
        if( $('select').length ){
            $('select').niceSelect();
        }

    })();


    jQuery(document).on('click','[data-toggle*=modal]',function(){
        jQuery('[role*=dialog]').each(function(){
            switch(jQuery(this).css('display')){
                case('block'):{jQuery('#'+jQuery(this).attr('id')).modal('hide'); break;}
            }
        });
    });

    $(document).ready(function(){

        // Dirty fix for jumping scrollbar when modal opens

        $('#requestModal').on('show.bs.modal', function (e) {
            if ($(window).width() > 768) {
                $(".navbar-default").css("padding-right","15px");
            }
        });

        $('#requestModal').on('hide.bs.modal', function (e) {
            if ($(window).width() > 768) {
                $(".navbar-default").css("padding-right","0px");
            }
        });

    });

    function toggleChevron(e) {
        $(e.target)
            .prev('.panel-heading')
            .find("i.indicator")
            .toggleClass('fa-angle-down fa-angle-up');
    }
    $('#accordion').on('hidden.bs.collapse', toggleChevron);
    $('#accordion').on('shown.bs.collapse', toggleChevron);

    function init() {
        window.addEventListener('scroll', function(e){
            var distanceY = window.pageYOffset || document.documentElement.scrollTop,
                shrinkOn = 100,
                header = document.querySelector("header");
            if (distanceY > shrinkOn) {
                classie.add(header,"smaller");
            } else {
                if (classie.has(header,"smaller")) {
                    classie.remove(header,"smaller");
                }
            }
        });
    }
    window.onload = init();

    $("#chatbox").click(function(e){
        e.preventDefault();
        $('body').toggleClass('chat-move');
        $('.chatwe').toggleClass('chat-height');
    });



    $(function () {

        var $activate_selectator4 = $('#activate_selectator4');
        $activate_selectator4.click(function () {
            var $select4 = $('.select4');
            if ($select4.data('selectator') === undefined) {
                $select4.selectator({
                    showAllOptionsOnFocus: true
                });
                $activate_selectator4.val('destroy selectator');
            } else {
                $select4.selectator('destroy');
                $activate_selectator4.val('activate selectator');
            }
        });
        $activate_selectator4.trigger('click');

    });


    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
    });



    $('head style[type="text/css"]');
    window.randomize = function() {
        $('.radial-progress').attr('data-progress', PRO_COMP);
        //$('.radial-progress').attr('data-progress', Math.floor(Math.random() * 100));
    }
    setTimeout(window.randomize, 200);
    $('.radial-progress').click(window.randomize);




    $(function() {
        if( $( "#slider-range" ).length ){

            $( "#slider-range" ).slider({
                range: true,
                min: 0,
                max: 500,
                values: [ 75, 300 ],
                slide: function( event, ui ) {
                    $( "#amount" ).val( "km" + ui.values[ 0 ] + " - km" + ui.values[ 1 ] );
                }
            });
            $( "#amount" ).val( "km" + $( "#slider-range" ).slider( "values", 0 ) +
                " - km" + $( "#slider-range" ).slider( "values", 1 ) );
        }

    });


    $(document).ready(function(){
        if( $('[data-toggle="tooltip"]').length ){
            $('[data-toggle="tooltip"]').tooltip();
        }



        $("#filter-toggle").click(function(){
            $(".open-div").toggle();
        });
        if( $('#ex18a').length ){
            $("#ex18a").slider({min  : 0, max  : 10, value: 5, labelledby: 'ex18-label-1'});
        }
        if( $('#ex18b').length ){
            $("#ex18b").slider({min  : 0, max  : 10, value: [3, 6], labelledby: ['ex18-label-2a', 'ex18-label-2b']});
        }

    });


    (function() {
        // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
        if (!String.prototype.trim) {
            (function() {
                // Make sure we trim BOM and NBSP
                var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                String.prototype.trim = function() {
                    return this.replace(rtrim, '');
                };
            })();
        }

        [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
            // in case the input is already filled..
            if( inputEl.value.trim() !== '' ) {
                classie.add( inputEl.parentNode, 'input--filled' );
            }

            // events:
            inputEl.addEventListener( 'focus', onInputFocus );
            inputEl.addEventListener( 'blur', onInputBlur );
        } );

        function onInputFocus( ev ) {
            classie.add( ev.target.parentNode, 'input--filled' );
        }

        function onInputBlur( ev ) {
            if( ev.target.value.trim() === '' ) {
                classie.remove( ev.target.parentNode, 'input--filled' );
            }
        }
    })();
});

$(document).ready(function(){
    if( $('[data-toggle="tooltip"]').length ){
        $('[data-toggle="tooltip"]').tooltip();
    }

});










