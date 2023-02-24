// Get the elements with the id "text" and "btn"
let text = document.getElementById("text");
let btn = document.getElementById("btn");

// Add an event listener to the window that triggers when the user scrolls
window.addEventListener("scroll", function(){
    // Get the current value of the scroll position
    let value = window.scrollY;
    // Update the "top" CSS property of the "text" element to move it up the page as the user scrolls
    text.style.top = 15 + value * 0.1 + "%";
    // Update the "top" CSS property of the "btn" element to move it up the page as the user scrolls
    btn.style.top = 43 + value * 0.1 + "%";
})

// Add an event listener to the "btn" element that triggers when the button is clicked
btn.addEventListener("click", function(){
    // Use the "scrollIntoView" method to smoothly scroll to the element with the id "bar"
    document.querySelector("#bar").scrollIntoView({
        behavior: "smooth"
    });
})
