(function($)
{

  $.widget('ui.sprint',
  {
    _init: function()
    {
        var self = this;
        
        this.element.find('td div.story').each(function()
        {
          $(this).draggable({
            distance:   0,
            containment: $(this).parent().parent(),
            revert: 'invalid'
          });
        });

        this.element.find('td').each(function()
        {
          var id = $(this).parent().attr('id');

          $(this).droppable({
            accept: '#'+id+' div.story',
            activeClass: 'droppable_active',
            hoverClass: 'droppable_hover',
            tolerance:    'intersect',
            drop: function(e, ui)
            {
                $.ajax({
                   type:    'POST',
                   url:     self.element.attr('data-move-url'),
                   data:    {
                     story_id: ui.draggable.attr('data-story-id'),
                     status:   $(this).attr('data-status')
                   }
                });
                ui.draggable.css({'left': 0, 'top': 0}).appendTo($(this));
             }
          });
       });
    }

  });
})(jQuery);
