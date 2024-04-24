var slider = document.getElementById('errorsInput');
var numberInput = document.getElementById('errorsNumberInput');

function updateNumberInput() {
  numberInput.value = slider.value * 100;
}

function updateSlider() {
  slider.value = numberInput.value / 100;
}

function updateSliderOnInputChange() {
  var value = parseFloat(numberInput.value);
  if (!isNaN(value)) {
    if (value > 1000) {
      numberInput.value = 1000;
    }
    slider.value = numberInput.value / 100;
  }
}

slider.addEventListener('input', updateNumberInput);
numberInput.addEventListener('input', updateSliderOnInputChange);
