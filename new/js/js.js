function resize(){
    var p = document.getElementById("parchment");
    var h = p.offsetWidth * 2971/2290;
    p.height = h + 'px';
}


function openModal() {
    document.getElementById('imgModal').style.display = "block";
}

function closeModal() {
    document.getElementById('imgModal').style.display = "none";
}

var slideIndex = 1;
//showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
//    var slides = document.getElementsByClassName("mySlides");
    var main = document.getElementById("mainImage");
    var imgs = document.getElementsByClassName("previewImage");
    var captionText = document.getElementById("caption");
    var number = document.getElementById("numbertext");
    var persons = document.getElementsByClassName("nodisplay");
    var personsInImage = document.getElementById("persons-in-image");
    if (n > imgs.length) {slideIndex = 1}
    if (n < 1) {slideIndex = imgs.length}
   /* for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    } */
    for (i = 0; i < imgs.length; i++) {
        imgs[i].className = imgs[i].className.replace(" active", "");
    }
    //slides[slideIndex-1].style.display = "block";
    main.src=imgs[slideIndex-1].src;
    imgs[slideIndex-1].className += " active";
    captionText.innerHTML = imgs[slideIndex-1].alt;
    number.innerHTML = slideIndex + "/" +imgs.length;
    personsInImage.innerHTML = persons[slideIndex-1].innerHTML;

    var m = document.getElementsByClassName("mainImage");
    var caption = document.getElementsByClassName("caption-container");
    var modal = document.getElementsByClassName("modal-content");
    modal[0].style.height = m[0].offsetHeight + caption[0].offsetHeight + 110 + "px";

}


function openDocModal() {
    document.getElementById('docModal').style.display = "block";
}

function closeDocModal() {
    document.getElementById('docModal').style.display = "none";
}
var docIndex = 1;

function currentDoc(n){
    showDoc(docIndex = n);
}

function plusDoc(n) {
    showDoc(docIndex+=n);
}

function showDoc(n) {
    var i;
    var documents = document.getElementsByClassName("docObjects");
    var li = document.getElementsByClassName("document");
    if(n > documents.length){ docIndex=1;}
    if (n < 1){docIndex = 1;}
    for(i=0; i<documents.length; i++){
        documents[i].style.display = "none";
    }
    documents[docIndex-1].style.display="block";

}