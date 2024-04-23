var errorsInput = document.getElementById('errorsInput');
var rangeValueSpan = document.getElementById('rangeValue');

errorsInput.addEventListener('input', function(event) {
  rangeValueSpan.textContent = errorsInput.value;
});
