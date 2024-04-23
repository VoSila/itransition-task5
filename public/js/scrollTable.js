let page = 1;
const perPage = 10;
let loading = false;

function buildUrlForScroll(region, errors , seed) {
  var url = 'https://localhost/?count=' + perPage + '&region=' + region + '&errors=' + errors;
  if (seed) {
    url += '&seed=' + (parseInt(seed) + page);
  }
  return url;
}

function loadData(url) {
  if (!loading) {
    loading = true;
    $.ajax({
      url: `${url}&page=${page}`,
      method: 'GET',
      success: function (responseText) {
        handleSuccessScroll(responseText);
        loading = false;
        page++;
      },
      error: function (xhr, status, error) {
        console.error('Error loading more data:', error);
        loading = false;
      }
    });
  }
}

function handleSuccessScroll(responseText) {
  var tempDiv = $('<div></div>').html(responseText);
  var newRows = tempDiv.find('#responseContainer').children();

  var currentRowCount = $('#responseContainer').children().length;

  newRows.each(function(index) {
    var rowNumber = currentRowCount + index + 1;
    $(this).prepend('<td>' + rowNumber + '</td>');
  });

  $('#responseContainer').append(newRows);
}

$(window).scroll(function () {
  if ($(window).scrollTop() + $(window).height() >= $(document).height() - 5 && $(window).scrollTop() > 0) {
    var regionValue = $('#regionSelect').val();
    var errorsValue = $('#errorsInput').val();
    var seedValue = $('#seedInput').val();

    var url = buildUrlForScroll(regionValue, errorsValue, seedValue);

    loadData(url);
  }
});

loadData();
