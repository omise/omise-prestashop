window.omiseDisplayMessage = function omiseDisplayMessage(message) {

  if ($.prototype.fancybox) {
    $.fancybox.open([{
      type: 'inline',
      autoScale: true,
      minHeight: 30,
      content: '<p class="fancybox-error">' + message + '</p>',
    }], { padding: 0 });
  } else {
    alert(message);
  }

}
