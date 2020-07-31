$(document).ready(function() {
  $('[name="menuProyecto"] a').addClass('active');
});

$(function(){
    // Remove svg.radial-progress .complete inline styling
    $('svg.radial-progress').each(function( index, value ) { 
        $(this).find($('circle.complete')).removeAttr( 'style' );
    });

    AnimarProgreso();
});

function AnimarProgreso(){
    setTimeout(function() {
        $('svg.radial-progress').each(function( index, value ) { 
          // If svg.radial-progress is approximately 25% vertically into the window when scrolling from the top or the bottom
            // Get percentage of progress
            percent = $(this).attr('data-percentage');
            if (percent > 80) {
                $(this).find($('circle.complete')).css('stroke', 'green');
            }
            else if (percent > 50){
                $(this).find($('circle.complete')).css('stroke', 'yellow');
            }
            else {
                $(this).find($('circle.complete')).css('stroke', 'red');
            }
            radius = $(this).find($('circle.complete')).attr('r');
            // Get circumference (2πr)
            circumference = 2 * Math.PI * radius;
            // Get stroke-dashoffset value based on the percentage of the circumference
            strokeDashOffset = circumference - ((percent * circumference) / 100);
            //$(this).find($('circle.complete')).css('stroke-dashoffset', circumference);
            // Transition progress for 1.25 seconds
            $(this).find($('circle.complete')).animate({'stroke-dashoffset': strokeDashOffset}, 1250);
        });    
    }, 500);
}