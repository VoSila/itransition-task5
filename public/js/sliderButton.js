var slider = document.getElementById('errorsInput');
var numberInput = document.getElementById('errorsNumberInput');

function updateNumberInput() {
  numberInput.value = slider.value;
}

function updateSlider() {
  slider.value = numberInput.value;
}

function updateSliderOnInputChange() {
  var value = parseFloat(numberInput.value);
  if (!isNaN(value)) {
    if (value > 1000) {
      numberInput.value = 1000;
    } else if (value < 0) {
      numberInput.value = 0;
    }
    slider.value = numberInput.value;
  }
}

slider.addEventListener('input', updateNumberInput);
numberInput.addEventListener('input', updateSlider);
numberInput.addEventListener('change', updateSliderOnInputChange);
