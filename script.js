const BUTTON_MAX_COUNT = 9;
const button_container = document.querySelector('.grid-container');

// Load button configurations from PHP server
async function Bootstrap() {
    const saved = await getAllHyperlinks();
    console.log('Retrieved hyperlinks:', saved); // Log the retrieved hyperlinks
    const buttons = [];

    // Clear existing buttons
    while (button_container.lastElementChild) {
        button_container.removeChild(button_container.lastElementChild);
    }

    // Create buttons
    for (let index = 0; index < BUTTON_MAX_COUNT; index++) {
        const gridItem = document.createElement('div');
        gridItem.classList.add('grid-item');

        const gridButton = document.createElement('button');
        gridButton.classList.add('grid-button');

        const editButton = document.createElement('button');
        editButton.classList.add('edit-button');

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('delete-button');

        gridItem.appendChild(gridButton);
        gridItem.appendChild(editButton);
        gridItem.appendChild(deleteButton);

        buttons.push(gridItem);
        button_container.appendChild(gridItem);
    }

    const buttons_visited = Array.from({ length: BUTTON_MAX_COUNT }, (_, i) => false);

    // Assign data from saved hyperlinks
    saved.forEach((hyperlink) => {
        const parent = buttons[hyperlink.position];

        buttons_visited[hyperlink.position] = true;
        const button = parent.querySelector('.grid-button');
        const editButton = parent.querySelector('.edit-button');
        const delButton = parent.querySelector('.delete-button');

        button.style.backgroundColor = hyperlink.color;
        button.textContent = hyperlink.title;

        // Open the hyperlink on button click
        button.addEventListener('click', () => {
            window.open(hyperlink.url, '_blank').focus();
        });

        button.style.backgroundImage = 'none';
        editButton.style.display = 'block';

        // Edit hyperlink
        editButton.addEventListener('click', () => {
            window.location.href = `editButton.html?hyperlink_id=${hyperlink.id}&position=${hyperlink.position}`;
        });

        // Delete hyperlink
        delButton.addEventListener('click', async () => {
            const currentTime = new Date(); // Get the current timestamp
            console.log('Deleting hyperlink:', hyperlink.id, 'with deleted_on:', currentTime); // Log the data being sent
            if (hyperlink.id) {
                await updateHyperlink(hyperlink.id, { deleted_on: currentTime }); // Update only the deleted_on field
            } else {
                console.error('No hyperlink ID found for deletion');
            }
            Bootstrap(); // Reload after deleting
        });

        delButton.style.display = 'block';
    });

    // Handle buttons with no hyperlinks
    buttons.forEach((parent, ind) => {
        if (buttons_visited[ind]) {
            return;
        }
        const button = parent.querySelector('.grid-button');
        const editButton = parent.querySelector('.edit-button');
        const delButton = parent.querySelector('.delete-button');

        button.style.backgroundImage = 'url("Assets/addImage.png")';
        button.style.backgroundColor = 'transparent';
        button.textContent = '';
        editButton.style.display = 'none';
        delButton.style.display = 'none';

        // Redirect to the edit button page for new entries
        button.addEventListener('click', () => {
            const position = Array.prototype.indexOf.call(button_container.children, parent);
            window.location.href = `editButton.html?position=${position}`;
        });
    });
}

// Function to fetch all hyperlinks from the PHP server
async function getAllHyperlinks() {
    try {
        const response = await fetch('getHyperlinks.php'); // PHP file to fetch all hyperlinks
        return await response.json();
    } catch (error) {
        console.error('Error fetching hyperlinks:', error);
        return [];
    }
}

// Function to update a hyperlink by sending a request to the PHP server
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

const urlParams = new URLSearchParams(window.location.search);
const hyperlinkId = urlParams.get('hyperlink_id');

// Fetch hyperlink data by ID
async function fetchHyperlinkData(id) {
    const response = await fetch(`getHyperlink.php?id=${id}`); // Create this PHP file
    return await response.json();
}

Bootstrap();