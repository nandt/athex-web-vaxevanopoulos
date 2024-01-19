(function ($) {

    Drupal.behaviors.ajaxFilteringBehavior = {
      attach: function (context, settings) {

        function handleClick() {
          $(this).addClass('active');
          setTimeout(function () {
            $('[id^="edit-submit-solutions"]').click();
          }, 1000);
        }
  
        $('[id^="views-exposed-form-solutions-solutions-overview"] .js-form-item:nth-child(2)', context).addClass('active');
  
        $('[id^="views-exposed-form-solutions-solutions-overview"] .js-form-item', context).click(handleClick);
      },
      once: function (context, settings) {
        
      }
    };
  
  })(jQuery);
  