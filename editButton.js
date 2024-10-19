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

    document.getElementById('title').value = hyperlink.title;
    document.getElementById('url').value = hyperlink.url;
    document.getElementById('color').value = hyperlink.color;
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
        deleted_on: null,
        position: position
    };
    
    if (hyperlinkId) {
        // Update hyperlink
        await updateHyperlink(hyperlinkId, config);
    } else {
        // Insert new hyperlink
        await insertHyperlink(config);
    }
    
    // Redirect back to the main page
    window.location.href = 'index.html';
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
        return await response.json();
    } catch (error) {
        console.error('Error inserting hyperlink:', error);
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
        return await response.json();
    } catch (error) {
        console.error('Error updating hyperlink:', error);
    }
}
