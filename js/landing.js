// var dropdownBtn = document.querySelectorAll(".section-2__plus_block");
var dropdownContent = document.querySelectorAll(".section-2__active_text");
var flexBox = document.querySelectorAll(".section-2__block_flex");
// var borderPlus = document.querySelectorAll(".plus__border-2");
// var blockInfo = document.querySelectorAll(".section-2__block_info");
// var text = document.querySelector(".section-2__block_flex");

// dropdownContent.forEach((element, index) => {
//     dropdownBtn[index].addEventListener("click", function() {
//         if (element.style.opacity == 0) {
//             flexBox[index].classList.add('show');
//             let show  = document.querySelectorAll('.show')
//             show[index].style.height = '100%'

//         } else {
//             document.querySelector('.show').style.height = 0

//         }
//     });
// });


var dropdownBtns = document.querySelectorAll(".section-2__plus_block");
var dropdownContents = document.querySelectorAll(".section-2__active_text");
var flexBox = document.querySelectorAll(".section-2__block_flex");

dropdownBtns.forEach((btn, index) => {
    btn.addEventListener("click", function() {
        if (dropdownContents[index].classList.contains("show")) {
            // Якщо вміст вже відображений, приховуємо його
            flexBox[index].style.marginBottom = "0px";
            dropdownContents[index].style.height = "0";
            dropdownContent[index].style.opacity = 0
            dropdownContents[index].classList.remove("show");
        } else {
            // Інакше відображаємо вміст та задаємо йому висоту
            dropdownContent[index].style.opacity = 1
            dropdownContents[index].classList.add("show");
            dropdownContents[index].style.height = "10vh";
            flexBox[index].style.marginBottom = 20 +'px';
        }
    });
});



// dropdownContent.forEach((element, index) => {
//     dropdownBtn[index].addEventListener("click", function() {
//         if (element.style.opacity == 0) {
//             flexBox[index].classList.add('show');
//             let show  = document.querySelectorAll('.show')
//             show[index].style.height = '100%'
//             // flexBox[index].style.marginBottom = 20 +'px';
//             // element.style.height = 150+'px'
//             // element.style.opacity = 1
//         } else {
//             document.querySelector('.show').style.height = 0
//             // element.style.opacity = 0
//             // element.style.height = 0 + 'px'
//             // flexBox[index].style.marginBottom = 0 
//         }
//     });
// });



