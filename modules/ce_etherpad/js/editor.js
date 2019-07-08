(function ($, Drupal, debounce, etherpad) {
    'use strict';
    // A page may contain multiple editors. editors variable store all of them as { id: editor_object }
    var editors = {};
    /**
     * @file
     * Defines Etherpad as a Drupal editor.
     */

    /**
     * Define editor methods.
     */
    if (Drupal.editors) Drupal.editors.ce_etherpad = {
        attach: function (element, format) {

          // Identifying the textarea as jQuery object.
          var $element = $(element);
          var element_id = $element.attr("id");

          // We don't delete the original textarea, but hide it.
          $element.hide().css('visibility', 'hidden');

          var $form = $(element.closest("form"));
          var form_action = $form.attr("action");
          var node = (form_action.split("node/"))[1].split('/')[0];

          $.ajax({
            url: drupalSettings.path.baseUrl + 'etherpad/get_pad',
            type: 'GET',
            contentType: "application/json; charset=utf-8",
            dataType: 'json',
            data: {nid: node},
            success : function(data) {
              var outer_div = "<div id='etherpad'></div>";
              $element.closest(".form-textarea-wrapper").append(outer_div);
              $('#etherpad').pad({
                'padId':data.pid,
                'host': format.editorSettings.host,
                'showChat': 'true',
                'showControls': 'true',
                'showLineNumbers': true,
                'userName': format.editorSettings.user
              });
            }
          }); 
        },
        detach: function (element, format, trigger) {
        },
        onChange: function (element, callback) {

          // Identifying the textarea as jQuery object.
          var $element = $(element);
          var element_id = $element.attr("id");

          window.setInterval(function(){
            var pad_id = $('#epframeetherpad').attr('src').split('?')[0].split('/p/')[1];
            $.ajax({
              url: drupalSettings.path.baseUrl + 'etherpad/get_content',
              type: 'GET',
              contentType: "application/json; charset=utf-8",
              dataType: 'json',
              data: {pid: pad_id},
              success : function(data) {
                var content = data.content;
                if(content != null) {
                  content = content.replace('<!DOCTYPE HTML><html><body>', '');
                  content = content.replace('</body></html>', '');
                  $element.attr('data-editor-value-original', content);
                  $element.html(content);
                }
              }
            });
          }, 1000);
        }
    };

})(jQuery, Drupal, Drupal.debounce);
