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

    fetch('http://localhost:8000/api/crawl', {
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

    data.forEach(category => {
        const categoryHeader = document.createElement('h3');
        categoryHeader.textContent = category.category;
        resultsContainer.appendChild(categoryHeader);
        
        category.products.forEach(product => {
            const productElement = document.createElement('div');

            productElement.innerHTML = `
                <p><strong>Toode:</strong> ${product.name}</p>
                <p><strong>Hind:</strong> ${product.price}</p>
                ${product.old_price ? `<p><strong>Vana hind:</strong> ${product.old_price}</p>` : ''}
                <p><strong>Allahindlus:</strong> ${product.discount}</p>
                <hr/>
            `;
            resultsContainer.appendChild(productElement);
        });
    });
};