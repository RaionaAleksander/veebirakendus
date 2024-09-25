// Create constant variables by getting elements from the HTML page
const form = document.getElementById('crawlForm');
const urlInput = document.getElementById('urlInput');
const resultsContainer = document.getElementById('results');

// Handle the form submit event
form.addEventListener('submit', function(event) {
    event.preventDefault(); // This function prevents the page from reloading

    const url = urlInput.value;

    if (!url) { // If this is not a URL link, then this condition is triggered
        resultsContainer.innerHTML = "<p style='color: red;'>Palun sisestage URL!</p>"
        return;
    }

    resultsContainer.innerHTML = "<p>Analüüs toimub... Palun oodake.</p>";

    fetch('/api/crawl', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ url: url})
    })
    .then(response => response.json())
    .then(data => {
        console.log("Siin näitame varsti midagi!")
        console.log(data);
        resultsContainer.innerHTML = "<p>Siin näitame varsti midagi!</p>";
    })
    .catch(error => {
        console.error('Tekkis viga: ', error);
        resultsContainer.innerHTML = "<p style='color: red;'>Tekkis viga. Palun proovi uuesti.</p>";
    });
});
