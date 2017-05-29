$(function () {
  $('.map-print').on('click',

  /*
   * Print dat maps!
   */
  function printMaps() {
    var body               = $('body');
    var mapContainer       = $('#map');
    var mapContainerParent = mapContainer.parent();
    var printContainer     = $('<div>');

    printContainer
      .addClass('print-container')
      .css('position', 'relative')
      .height(mapContainer.height())
      .append(mapContainer)
      .prependTo(body);

    var content = body
      .children()
      .not('script')
      .not(printContainer)
      .detach();
      
    // Patch for some Bootstrap 3.3.x `@media print` styles. :|
    var patchedStyle = $('<style>')
      .attr('media', 'print')
      .text('img { max-width: none !important; }' +
            'a[href]:after { content: ""; }')
      .appendTo('head');

    window.print();

    body.prepend(content);
    mapContainerParent.prepend(mapContainer);

    printContainer.remove();
    patchedStyle.remove();
  });
});