// alert("hola 3 ");  
console.log('popstate fired!');
window.addEventListener('popstate', function(event) {
  console.log('popstate fired!');
  location.replace(window.location.href);
});