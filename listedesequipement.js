document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const content = document.getElementById('content');
    const paragraphs = content.getElementsByTagName('p');

    // Remove previous highlights
    for (let p of paragraphs) {
        p.innerHTML = p.innerHTML.replace(/<span class="highlight">(.*?)<\/span>/g, '$1');
    }

    // Highlight new search terms
    if (searchText) {
        for (let p of paragraphs) {
            let text = p.innerHTML;
            const regex = new RegExp(`(${searchText})`, 'gi');
            p.innerHTML = text.replace(regex, '<span class="highlight">$1</span>');
        }
    }
});