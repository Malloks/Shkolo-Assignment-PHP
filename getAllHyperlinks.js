// Function to fetch all hyperlinks from the PHP server
async function getAllHyperlinks() {
    try {
        const response = await fetch('getHyperlinks.php');

        // Check if the response is OK (status in the range 200-299)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.error) {
            console.error('Error fetching hyperlinks:', data.error);
            return [];
        }
        
        return data;
    } catch (error) {
        console.error('Error fetching hyperlinks:', error);
        return [];
    }
}
