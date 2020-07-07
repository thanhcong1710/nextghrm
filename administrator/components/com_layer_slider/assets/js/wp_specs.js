imgpath = "";

function openModal(a){
  window.imgInput = jQuery(a).parent().prev();
  window.imgNode = jQuery(a).children().children();
  SqueezeBox.open('index.php?option=com_media&view=images&tmpl=component&folder='+imgpath+'&e_name=image',{handler:'iframe',size:{x:800,y:600}});
}

function jInsertEditorText(tag, name) {
  var $tag = jQuery(tag);
  imgpath = $tag.attr('src').match(/images\/?(.*)\//)[1] || "";
  imgInput.val(joomla_base_url + $tag.attr('src'));
  imgNode.attr('src', joomla_base_url + $tag.attr('src'));
  LayerSlider.willGeneratePreview( jQuery('.ls-box.active').index() );
}

ajaxsaveurl = 'index.php?option=com_layer_slider&view=slider&task=save_slider';

(function($, undefined) {

  $.fn.inputsToObj = function() {
    var obj = {};
    this.find(':input').each(function() {
      obj[this.name] = this.type == 'checkbox' ? this.checked : this.value;
    });
    return JSON.stringify(obj);
  };

  $.fn.objToInputs = function(obj) {
    if (!obj) return alert("There is nothing to paste!");
    obj = JSON.parse(obj);
    var $input, prop;
    for (prop in obj) {
      $input = this.find('[name="'+prop+'"]');
      if (typeof obj[prop] == 'boolean') {
        if ($input[0].checked != obj[prop]) $input.next().click();
      }
      else $input.val(obj[prop]);
    }
    $input.trigger("keyup");
  };

  $.ui.draggable.prototype.options.delay = 100;
  $.ui.sortable.prototype.options.delay = 100;

})(jQuery);


jQuery(function($) {
  // init selectable
  var selected = [],
      activeTab = 0,
      $activeTab = $('.ls-layer-box.active');
  $('#ls-pages')
    .on('mousedown', '.ls-preview .ui-draggable', function(e) {
      e.stopPropagation();
      $activeTab.find('.ls-selected').removeClass('ls-selected');
      selected[activeTab] = $(this).addClass('ls-selected').index();
    })
    .on("mousedown", ".ls-preview-td", function(e) {
      e.stopPropagation();
      $activeTab.find('.ls-selected').removeClass('ls-selected');
      selected[activeTab] = undefined;
    })
    .on('mouseup', '.ls-sublayers > tr', function(e) {
      var i = $(this).index();
      $activeTab.find('.ls-layer > :eq('+i+')').trigger('mousedown');
    })
    // handle tab changing
    .on('click', '#ls-layer-tabs a', function(e) {
      activeTab = $(this).index();
      $activeTab = $('.ls-layer-box.active');
      var i = selected[activeTab];
      if (i) $activeTab.find('.ls-layer > :eq('+i+')').trigger('mousedown');
    });

  $(document.body).on('keypress', function(e) {
    if ($(e.target).is(':input')) return;
    var $layer = $('.ls-layer-box.active .ls-selected');
    if (!$layer.length) return;
    var i = 0, j = 0;
    switch (e.keyCode) {
      case 37: i--; break; // left
      case 38: j--; break; // up
      case 39: i++; break; // right
      case 40: j++; break; // down
      case 46: // delete
        return $activeTab.find('tr.active .remove').click();
    }
    if (i) {
      var x = parseInt($layer.css('left'));
      x += e.shiftKey? i*10 : i;
      $layer.css('left', x);
      var i = selected[activeTab];
      if (i) $activeTab.find('input[name=left]:eq('+i+')').val(x + 'px');
      return e.preventDefault();
    }
    if (j) {
      var y = parseInt($layer.css('top'));
      y += e.shiftKey? j*10 : j;
      $layer.css('top', y);
      var i = selected[activeTab];
      if (i) $activeTab.find('input[name=top]:eq('+i+')').val(y + 'px');
      return e.preventDefault();
    }
  });

  // INIT HELP
  $('#screen-meta').on('click', '.contextual-help-tabs a', function(e) {
    var $this = $(this);
    $('#screen-meta .active').removeClass('active');
    $this.addClass('active');
    $($this.attr('href')).addClass('active');
    e.preventDefault();
  });
  // init first tab
  $('.contextual-help-tabs a:first, .help-tab-content:first').addClass('active');
  $('.show-settings').on("click", function(e) {
    $($(this).attr('href')).slideToggle(250);
    e.preventDefault();
  });
});