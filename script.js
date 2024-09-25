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
        displayResults(data);
    })
    .catch(error => {
        console.error('Tekkis viga: ', error);
        resultsContainer.innerHTML = "<p style='color: red;'>Tekkis viga. Palun proovi uuesti.</p>";
    });
});

function displayResults(data) {
    resultsContainer.innerHTML = '';

    if (data.products && data.products.length > 0) {
        const productList = document.createElement('ul');
        data.products.forEach(product => {
            const listItem = document.createElement('li');
            listItem.textContent = `${product.name} - Hind: ${product.price}`;
            productList.appendChild(listItem);
        })

        resultsContainer.appendChild(productList);
    } else {
        resultsContainer.innerHTML = '<p>Tooted ei leitud.</p>'
    }
};