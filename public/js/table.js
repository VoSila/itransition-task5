$(document).ready(function() {
  $('#randomSeed').click(function() {
    var randomNumber = Math.floor(Math.random() * 100000) + 1;
    $('#seedInput').val(randomNumber);

    sendDataToServer();
  });

  $(document).ready(function() {
    $('#seedInput').on('keydown', function(event) {
      var keyCode = event.keyCode || event.which;

      if ((keyCode < 48 || keyCode > 57) && keyCode !== 8 && keyCode !== 9 && keyCode !== 37 && keyCode !== 39 && keyCode !== 46) {
        event.preventDefault();
      }
    });
  });

  function sendDataToServer() {
    page = 1;
    var regionValue = $('#regionSelect').val();
    var errorsValue = $('#errorsNumberInput').val();
    var seedValue = $('#seedInput').val();

    var url = buildUrl(regionValue, errorsValue, seedValue);

    sendRequest(url);
  }

  function buildUrl(region, errors, seed) {
    if(!seed){
      seed = '740';
    }
    return 'https://localhost/?count=10&region=' + region + '&errors=' + errors + '&seed=' + seed;
  }

  function sendRequest(url) {
    $.ajax({
      url: url,
      method: 'GET',
      success: function(responseText) {
        handleSuccess(responseText);
      },
      error: function(xhr, status) {
        console.log(xhr.status);
      }
    });
  }

  function handleSuccess(responseText) {
    var tempDiv = $('<div></div>').html(responseText);
    var newRows = tempDiv.find('#responseContainer').children();

    newRows.each(function(index) {
      var rowNumber = index + 1;
      $(this).prepend('<td>' + rowNumber + '</td>');
    });

    var tbodyContent = tempDiv.find('#responseContainer').html();

    $('#responseContainer').html(tbodyContent);
  }

  document.querySelector('form').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      event.preventDefault();
    }
  });

  document.getElementById('regionSelect').addEventListener('change', sendDataToServer);
  document.getElementById('errorsInput').addEventListener('change', sendDataToServer);
  document.getElementById('errorsNumberInput').addEventListener('change', sendDataToServer);
  document.getElementById('seedInput').addEventListener('change', sendDataToServer);
});
