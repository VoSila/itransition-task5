const slider = document.getElementById('errorsInput');
const field = document.getElementById('errorsNumberInput');

// Функция обновления поля ввода при изменении слайдера
function updateNumberInput() {
  field.value = slider.value;
}

// Функция обновления слайдера при изменении поля ввода
function updateSlider() {
  slider.value = Math.min(field.value, 10);
}

// Функция обновления слайдера при изменении поля ввода и проверка на допустимые значения
function updateSliderOnInputChange() {
  let value = parseFloat(field.value);
  if (!isNaN(value)) {
    // Ограничиваем значение поля ввода до 1000
    if (value > 1000) {
      field.value = 1000;
    } else if (value < 0) {
      field.value = 0;
    }
    // Ограничиваем значение слайдера до 10
    slider.value = Math.min(field.value, 10);
  }
}

// Слушатель события изменения слайдера
slider.addEventListener('input', updateNumberInput);

// Слушатель события изменения поля ввода
field.addEventListener('input', updateSlider);

// Слушатель события изменения поля ввода для проверки допустимых значений
field.addEventListener('change', updateSliderOnInputChange);
