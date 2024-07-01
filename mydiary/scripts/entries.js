function validateSearch() {
    var searchInput = document.getElementById("search").value;
    if (searchInput.trim() === "") {
        alert("Please enter a search term.");
        return false;
    }
    return true;
}

window.onload = function() {
    var errorMessage = document.getElementById("errorMessage");
    if (errorMessage) {
        setTimeout(function() {
            errorMessage.style.display = 'none';
        }, 5000); // Hide after 5 seconds
    }
    
    // Remove the search parameter from the URL
    var url = new URL(window.location.href);
    if (url.searchParams.has("search")) {
        url.searchParams.delete("search");
        window.history.replaceState({}, document.title, url.pathname + url.search);
    }
};
