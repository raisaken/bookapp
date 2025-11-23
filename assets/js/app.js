// Simple autocomplete for title search using fetch
document.addEventListener('DOMContentLoaded', function() {
  const q = document.getElementById('q');
  if (!q) return;
  let box;
  q.addEventListener('input', function() {
    const term = q.value.trim();
    if (term.length < 2) { removeBox(); return; }
    fetch('?p=ajax/titles&term=' + encodeURIComponent(term))
      .then(r => r.json())
      .then(data => {
        removeBox();
        box = document.createElement('div');
        box.className = 'border bg-white mt-1 absolute z-50';
        box.style.maxWidth = '400px';
        data.forEach(item => {
          const div = document.createElement('div');
          div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
          div.textContent = item.title;
          div.addEventListener('click', () => {
            q.value = item.title;
            removeBox();
          });
          box.appendChild(div);
        });
        q.parentNode.appendChild(box);
      }).catch(err => { console.error(err); });
  });

  document.addEventListener('click', function(e) {
    if (box && !q.contains(e.target)) removeBox();
  });
  function removeBox() { if (box && box.parentNode) box.parentNode.removeChild(box); box = null; }
});
