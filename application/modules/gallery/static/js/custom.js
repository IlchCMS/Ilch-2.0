/* Isotope Portfolio Filter */
// quick search regex
var qsRegex;
var buttonFilter;
// init
var $grid = $('#gallery').isotope({
  itemSelector: '.lib-item',
  layoutMode: 'fitRows',
  getSortData: { name: '.lib-header' },
  filter: function() {
    var $this = $(this);
    var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
    var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
    return searchResult && buttonResult;
  }
});
// filter button
$('#filters').on( 'click', 'li a', function() {
  buttonFilter = $( this ).attr('data-filter');
  $grid.isotope();
});
// sort button
$('#sorts').on( 'click', 'a', function() {
  var sortValue = $(this).attr('data-sort-by');
  var direction = $(this).attr('data-sort-direction');
  var isAscending = (direction == 'asc');
  var newDirection = (isAscending) ? 'desc' : 'asc';
  $grid.isotope({ sortBy: sortValue, sortAscending: isAscending });
  $(this).attr('data-sort-direction', newDirection); 
  var span = $(this).find('.sorticon');
  span.toggleClass('fa-sort-numeric-desc fa-sort-numeric-asc');
});
// debounce filtering
function debounce( fn, threshold ) {
  var timeout;
  threshold = threshold || 100;
  return function debounced() {
    clearTimeout( timeout );
    var args = arguments;
    var _this = this;
    function delayed() {
      fn.apply( _this, args );
    }
    timeout = setTimeout( delayed, threshold );
  };
}
// search field
var $quicksearch = $('#quicksearch').keyup( debounce( function() {
  qsRegex = new RegExp( $quicksearch.val(), 'gi' );
  $grid.isotope();
}) );
// checked class on buttons
$('.dropdown-menu').each( function( i, buttonGroup ) {
  var $buttonGroup = $( buttonGroup );
  $buttonGroup.on('click', 'li', function() {
    $buttonGroup.find('.active').removeClass('active');
	var text = $(this).text();
    $('.catinfo').html('&raquo; ').append(document.createTextNode(text));
    $('.dropdown-toggle').html('<i class="fa-solid fa-image"></i> ').append(document.createTextNode(text));
    $(this).addClass('active');
  });
});
