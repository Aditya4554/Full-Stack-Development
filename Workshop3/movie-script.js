const API_URL = 'http://localhost:3000/movies';
const movieListDiv = document.getElementById('movie-list');
const searchInput = document.getElementById('search-input');
const form = document.getElementById('add-movie-form');

let allMovies = []; // Stores the full, unfiltered list of movies

// Function to dynamically render movies to the HTML
function renderMovies(moviesToDisplay) {
    movieListDiv.innerHTML = '';
    
    if (moviesToDisplay.length === 0) {
        movieListDiv.innerHTML = '<p>No movies found matching your criteria.</p>';
        return;
    }
    
    moviesToDisplay.forEach(movie => {
        const movieElement = document.createElement('div');
        movieElement.classList.add('movie-item');
        movieElement.innerHTML = `
            <p><strong>${movie.title}</strong> (${movie.year}) - ${movie.genre}</p>
            <button onclick="editMoviePrompt(${movie.id}, '${movie.title}', ${movie.year}, '${movie.genre}')">Edit</button>
            <button onclick="deleteMovie(${movie.id})">Delete</button>
        `;
        movieListDiv.appendChild(movieElement);
    });
}

// Function to fetch all movies and store them (READ)
function fetchMovies() {
    fetch(API_URL)
        .then(response => response.json())
        .then(movies => {
            allMovies = movies; // Store the full list
            renderMovies(allMovies); // Display the full list
        })
        .catch(error => console.error('Error fetching movies:', error));
}

// Search Functionality
searchInput.addEventListener('input', function() {
    const searchTerm = searchInput.value.toLowerCase();
    const filteredMovies = allMovies.filter(movie => {
        return movie.title.toLowerCase().includes(searchTerm) || 
               movie.genre.toLowerCase().includes(searchTerm);
    });
    renderMovies(filteredMovies);
});

// CREATE - Add a new movie (POST Method)
form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    const newMovie = {
        title: document.getElementById('title').value,
        genre: document.getElementById('genre').value,
        year: parseInt(document.getElementById('year').value)
    };
    
    fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(newMovie)
    })
    .then(response => response.json())
    .then(() => {
        fetchMovies(); // Refresh the list
        form.reset(); // Clear the form
    })
    .catch(error => console.error('Error adding movie:', error));
});

// UPDATE - Edit a movie (PUT Method)
function editMoviePrompt(id, currentTitle, currentYear, currentGenre) {
    const newTitle = prompt('Enter new title:', currentTitle);
    const newGenre = prompt('Enter new genre:', currentGenre);
    const newYear = prompt('Enter new year:', currentYear);
    
    if (newTitle && newYear) {
        const updatedMovie = {
            title: newTitle,
            genre: newGenre || currentGenre,
            year: parseInt(newYear)
        };
        
        fetch(`${API_URL}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedMovie)
        })
        .then(response => response.json())
        .then(() => {
            fetchMovies(); // Refresh the list
        })
        .catch(error => console.error('Error updating movie:', error));
    }
}

// DELETE - Remove a movie (DELETE Method)
function deleteMovie(id) {
    if (confirm('Are you sure you want to delete this movie?')) {
        fetch(`${API_URL}/${id}`, {
            method: 'DELETE'
        })
        .then(() => {
            fetchMovies(); // Refresh the list
        })
        .catch(error => console.error('Error deleting movie:', error));
    }
}

// Initial load
fetchMovies();