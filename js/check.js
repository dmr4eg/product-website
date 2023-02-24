window.onload = function () {
  // Get the input element with the id "name"
  var inputEL = document.querySelector('#name');
  // Add an event listener to the input element that triggers when the user releases a key
  inputEL.addEventListener('keyup', checkName);
  
  // Get the element with the id "message"
  var messageEL = document.querySelector('#message');
  
  // Define a function that checks if the name already exists in the json data
  function checkName() {
      // Use fetch to send a request to the json/data.json file
      fetch('json/data.json')
      // Parse the response as JSON
      .then(response => response.json())
      // Check if the name already exists in the data
      .then(data => {
        const nameExists = data.some(name => name === inputEL.value);
        if (nameExists) {
          // If the name already exists, update the message to show that the name is not available
          messageEL.textContent = "Name already exists";
        } else {
          // If the name is available, update the message to show that the name is available
          messageEL.textContent = "Name is available";
        }
      })
      .catch(error => console.error(error));
  }
};
