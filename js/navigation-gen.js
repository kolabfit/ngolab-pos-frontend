// Function to load navigation from nav.json
async function loadNavigation() {
    try {
        const response = await fetch('../json/nav.json');
        const navData = await response.json();

        const menu = document.getElementById('menu');

        // Loop through each nav item
        navData.items.forEach(item => {
            const li = document.createElement('li');

            const link = document.createElement('a');
            link.href = item.url;
            link.className = '';
            link.setAttribute('aria-expanded', 'false');

            // Icon
            const icon = document.createElement('i');
            icon.className = `fas ${item.icon}`;

            // Nav text
            const span = document.createElement('span');
            span.className = 'nav-text';
            span.textContent = item.label;

            // Append icon and text to the link
            link.appendChild(icon);
            link.appendChild(span);

            // If the current URL matches the navigation item's URL, add mm-active class
            const path = document.body.getAttribute('data-page')
            console.log(item.url);
            if (item.url === path) {
                li.classList.add('mm-active');
                link.classList.add('mm-active');
            }

            // Append link to li and li to ul
            li.appendChild(link);
            menu.appendChild(li);
        });
    } catch (error) {
        console.error('Failed to load navigation:', error);
    }
}

// Load navigation when the DOM content is fully loaded
document.addEventListener('DOMContentLoaded', loadNavigation);
