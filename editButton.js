const urlParams = new URLSearchParams(window.location.search);
const hyperlinkId = urlParams.get('hyperlink_id');
const position = urlParams.get('position');

document.addEventListener('DOMContentLoaded', async () => {
    if (!hyperlinkId) {
        document.querySelector('#page-title').textContent = 'Create Button Configuration';
        document.querySelector('title').textContent = 'Create Button Configuration';
        return;
    }

    // Fetch the hyperlink from the PHP backend
    const hyperlink = await getHyperlink(hyperlinkId);

    if (hyperlink) {
        document.getElementById('title').value = hyperlink.title;
        document.getElementById('url').value = hyperlink.url;
        document.getElementById('color').value = hyperlink.color;
    }
});

// Handle form submission
document.getElementById('edit-form').addEventListener('submit', async (event) => {
    event.preventDefault();

    const title = document.getElementById('title').value;
    const url = document.getElementById('url').value;
    const color = document.getElementById('color').value;

    const config = {
        title: title,
        url: url,
        color: color,
        position: position
    };

    try {
        if (hyperlinkId) {
            // Update hyperlink
            await updateHyperlink(hyperlinkId, config);
        } else {
            // Insert new hyperlink
            await insertHyperlink(config);
        }

        // Redirect back to the main page
        window.location.href = 'index.html';
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while saving the hyperlink. Please try again later.');
    }
});

// Fetch a single hyperlink by ID (PHP endpoint)
async function getHyperlink(id) {
    try {
        const response = await fetch('getHyperlink.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        });
        return await response.json();
    } catch (error) {
        console.error('Error fetching hyperlink:', error);
        return null;
    }
}

// Insert a new hyperlink (PHP endpoint)
async function insertHyperlink(config) {
    try {
        const response = await fetch('insertHyperlink.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(config)
        });

        const text = await response.text(); // Get raw text
        console.log("Raw response: ", text); // Log raw response

        if (!response.ok) {
            throw new Error('Failed to insert hyperlink');
        }

        const data = JSON.parse(text); // Attempt to parse JSON
        console.log("Document written with ID: ", data.id);
        return data.id;
    } catch (error) {
        console.error("Error adding document: ", error);
        throw error; // Re-throw the error to handle it in the form submission
    }
}

// Update an existing hyperlink (PHP endpoint)
async function updateHyperlink(docId, updatedData) {
    try {
        const response = await fetch('updateHyperlink.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ docId, updatedData })
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error("Error response: ", errorText);
            throw new Error('Failed to update hyperlink');
        }

        console.log("Document successfully updated!");
    } catch (error) {
        console.error("Error updating hyperlink:", error);
        alert('Try again later');
    }
}

async function fetchHyperlinkData(docId) {
    try {
        const response = await fetch(`getHyperlink.php?id=${docId}`); // Fetch hyperlink data from PHP
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return await response.json();
    } catch (error) {
        console.error('Error fetching hyperlink data:', error);
        return { error: 'Error fetching hyperlink data' }; // Return error object
    }
}

async function preFillForm() {
    const params = new URLSearchParams(window.location.search);
    const hyperlinkId = params.get('hyperlink_id');

    if (hyperlinkId) {
        const hyperlinkData = await fetchHyperlinkData(hyperlinkId);

        console.log('Fetched hyperlink data:', hyperlinkData); // Log the fetched data

        if (hyperlinkData && !hyperlinkData.error) {
            const titleInput = document.getElementById('title');
            const urlInput = document.getElementById('url');
            const colorInput = document.getElementById('color');
            const positionInput = document.getElementById('position');

            if (titleInput) titleInput.value = hyperlinkData.title || ''; // Use empty string as fallback
            if (urlInput) urlInput.value = hyperlinkData.url || '';

            // Ensure color is a valid hex code or default to black
            const colorValue = hyperlinkData.color || '#000000'; // Default to black if undefined
            if (/^#([0-9A-F]{3}){1,2}$/i.test(colorValue)) {
                if (colorInput) colorInput.value = colorValue;
            } else {
                if (colorInput) colorInput.value = '#000000'; // Fallback color
            }

            if (positionInput) positionInput.value = hyperlinkData.position || '';
        } else {
            console.error('Error fetching hyperlink data:', hyperlinkData.error);
        }
    } else {
        console.error('No hyperlink ID found in URL');
    }
}

// Call preFillForm when the page loads
document.addEventListener('DOMContentLoaded', preFillForm);
// Fetch all hyperlinks (you may want to implement this in a separate function)
// This function has been removed as it wasn't used in this context