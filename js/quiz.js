function swapElem(el1, el2) {
  let prev1 = el1.previousSibling;
  let prev2 = el2.previousSibling;

  prev1.after(el2);
  prev2.after(el1);
}

function showNextForm() {
  var currentForm = document.querySelector('.form:not([style="display: none;"])');
  var nextForm = currentForm.nextElementSibling;
  var next_button = document.querySelector('#next_button');
  var save_button = document.querySelector('#save_button');

  if (nextForm.nextElementSibling.className !== 'form')
  {
    next_button.classList.add('display_none');
    save_button.classList.remove('display_none');
    swapElem(next_button,save_button);
  }
  if (nextForm !== null && nextForm.className === 'form') {
    currentForm.style.display = 'none';
    nextForm.style.display = 'block';
  } else {

    console.log('This is the last form.');
  }
}

document.addEventListener('DOMContentLoaded', function () {
  var returnButton = document.querySelector('.next');
  if (returnButton) {
    returnButton.addEventListener('click', showNextForm);
  }
});



document.addEventListener('DOMContentLoaded', function () {
  var returnButton = document.querySelector('.return');
  if (returnButton) {
    returnButton.addEventListener('click', showPreviousForm);
  }
});

function showPreviousForm() {
  var currentForm = document.querySelector('.form:not([style="display: none;"])');
  var previousForm = currentForm.previousElementSibling;
  var next_button = document.querySelector('#next_button');
  var save_button = document.querySelector('#save_button');

  if (currentForm.nextElementSibling.className !== 'form')
  {
    next_button.classList.remove('display_none');
    save_button.classList.add('display_none');
    swapElem(next_button,save_button);
  }

  if (previousForm && previousForm.className === 'form') {
    currentForm.style.display = 'none';
    previousForm.style.display = 'block';
  } else {
    console.log('This is the first form.');
  }
}