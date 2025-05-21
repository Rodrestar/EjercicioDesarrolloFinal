let wordFrequencies = JSON.parse(sessionStorage.getItem('wordFrequencies')) || {};

document.addEventListener('DOMContentLoaded', function () {
    const analyzeBtn = document.getElementById('analizarBtn');
    analyzeBtn.addEventListener('click', analyzeText);
    displayResults();
});

function analyzeText() {
    const textInput = document.getElementById('text-input');
    const text = textInput.value.trim();

    // Enviar el texto al backend (sin validación, solo crudo)
    fetch('../php/analyze.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text: text }) // Enviar texto crudo al backend
    })
    .then(response => response.json())
    .then(data => {
        // Si el backend devuelve un error, se muestra el mensaje
        if (data.error) {
            alert(data.error);
            return;
        }

        // Acumular las frecuencias durante la sesión actual
        for (const [word, freq] of Object.entries(data)) {
            if (wordFrequencies[word]) {
                wordFrequencies[word] += freq;
            } else {
                wordFrequencies[word] = freq;
            }
        }
        saveFrequencies();
        displayResults();
    })
    .catch(error => {
        console.error('Error al analizar el texto:', error);
        alert('Error al analizar el texto. Revisa la consola para más detalles.');
    });
}

function displayResults() {
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = '';

    if (!wordFrequencies || Object.keys(wordFrequencies).length === 0) {
        resultDiv.innerHTML = '<p>No se encontraron palabras significativas.</p>';
        return;
    }

    let output = '<table class="table table-bordered table-striped"><thead><tr><th>Palabra</th><th>Frecuencia</th></tr></thead><tbody>';
    for (const [word, frequency] of Object.entries(wordFrequencies)) {
        output += `<tr><td>${word}</td><td>${frequency}</td></tr>`;
    }
    output += '</tbody></table>';
    resultDiv.innerHTML = output;
}

function saveFrequencies() {
    sessionStorage.setItem('wordFrequencies', JSON.stringify(wordFrequencies));
}
function clearFrequencies() {
    wordFrequencies = {};
    sessionStorage.removeItem('wordFrequencies');
    displayResults();
}