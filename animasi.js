const menuKategori = document.querySelectorAll(".menu-kategori");

menuKategori.forEach((kategori) => {
  kategori.addEventListener("mouseover", () => {
    kategori.style.transform = "scale(1.1)"; // Zoom on mouseover
  });

  kategori.addEventListener("mouseout", () => {
    kategori.style.transform = "scale(1)"; // Reset zoom on mouseout
  });
});
 const checkoutButton = document.querySelector('.chechkout-button');
 checkoutButton.disabled = true;

 const form = document.querySelector('#checkoutForm');

 form.addEventListener('keyup' , function() {
for(let i = 0; i < form.element.length; i++) {
  if(form.elements[i].value.length !==0){
    checkoutButton.classList.remove('disabled');
    checkoutButton.classList.add('disabled');
  } else {
    return false;

  }
}
checkoutButton.disabled = false;
checkoutButton.classList.remove('disabled');
 });
checkoutButton.addEventListener('click' , function (e) {
  e.preventDefault



})