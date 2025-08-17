// DOM elements
const bookForm = document.getElementById('bookForm');
const messageDiv = document.getElementById('message');
const tableBody = document.getElementById('tableBody');
const noBooksDiv = document.getElementById('noBooks');
const loadingSpinner = document.getElementById('loadingSpinner');
const submitBtn = document.querySelector('.submit-btn');
const toggleTableBtn = document.getElementById('toggleTableBtn');
const toggleBtnText = document.getElementById('toggleBtnText');
const toggleBtnIcon = document.getElementById('toggleBtnIcon');
const tableSection = document.querySelector('.table-section');

// API endpoints
const API_BASE = window.location.origin + window.location.pathname.replace('index.html', '');
const ADD_BOOK_URL = API_BASE + 'add_book.php';
const GET_BOOKS_URL = API_BASE + 'get_books.php';
const DELETE_BOOK_URL = API_BASE + 'delete_book.php';
const UPDATE_BOOK_URL = API_BASE + 'update_book.php';

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    setupFormSubmission();
    setupToggleButton();
});

// Setup form submission handler
function setupFormSubmission() {
    bookForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(bookForm);
        const bookData = {
            title: formData.get('title').trim(),
            author: formData.get('author').trim(),
            genre: formData.get('genre'),
            best_selling: formData.get('best_selling') === 'on'
        };
        
        // Validate form data
        if (!validateFormData(bookData)) {
            return;
        }
        
        // Show loading state
        setLoadingState(true);
        
        try {
            const response = await fetch(ADD_BOOK_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(bookData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showMessage('Book added successfully!', 'success');
                bookForm.reset();
                loadBooks(); // Reload the books table
            } else {
                showMessage(result.message || 'Error adding book', 'error');
            }
            } catch (error) {
                console.error("Error:", error);
                // Removed network error message as per user request
            } finally {
                setLoadingState(false);
            }
    });
}

// Setup toggle button functionality
function setupToggleButton() {
    toggleTableBtn.addEventListener('click', toggleTableVisibility);
}

// Toggle table visibility
function toggleTableVisibility() {
    const isVisible = tableSection.classList.contains('show');
    
    if (isVisible) {
        // Hide table
        tableSection.classList.remove('show');
        toggleTableBtn.classList.remove('active');
        toggleBtnText.textContent = 'Show Book Collection';
        toggleBtnIcon.textContent = '▼';
    } else {
        // Show table
        tableSection.classList.add('show');
        toggleTableBtn.classList.add('active');
        toggleBtnText.textContent = 'Hide Book Collection';
        toggleBtnIcon.textContent = '▲';
        
        // Load books when showing table for the first time
        loadBooks();
    }
}

// Validate form data
function validateFormData(data) {
    if (!data.title) {
        showMessage('Title is required', 'error');
        return false;
    }
    
    if (!data.author) {
        showMessage('Author is required', 'error');
        return false;
    }
    
    if (!data.genre) {
        showMessage('Please select a genre', 'error');
        return false;
    }
    
    return true;
}

// Set loading state for form submission
function setLoadingState(loading) {
    if (loading) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
    } else {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
    }
}

// Show message to user
function showMessage(text, type) {
    messageDiv.textContent = text;
    messageDiv.className = `message ${type}`;
    messageDiv.classList.add('show');
    
    // Hide message after 5 seconds
    setTimeout(() => {
        messageDiv.classList.remove('show');
    }, 5000);
}

// Load books from the database
async function loadBooks() {
    try {
        const response = await fetch(GET_BOOKS_URL);
        const result = await response.json();
        
        if (result.success) {
            displayBooks(result.data);
        } else {
            console.error('Error loading books:', result.message);
            showMessage('Error loading books', 'error');
        }
    } catch (error) {
        console.error("Error:", error);
        // Removed network error message as per user request
    }
}

// Display books in the table
function displayBooks(books) {
    // Clear existing table content
    tableBody.innerHTML = '';
    
    if (books.length === 0) {
        noBooksDiv.classList.add('show');
        return;
    }
    
    noBooksDiv.classList.remove('show');
    
    books.forEach((book, index) => {
        const row = createBookRow(book, index);
        tableBody.appendChild(row);
    });
}

// Create a table row for a book
function createBookRow(book, index) {
    const row = document.createElement('tr');
    
    // Create best seller badge
    const bestSellerBadge = book.best_selling 
        ? '<span class="best-seller-badge best-seller-yes">Yes</span>'
        : '<span class="best-seller-badge best-seller-no">No</span>';
    
    row.innerHTML = `
        <td>${book.id}</td>
        <td><strong>${escapeHtml(book.title)}</strong></td>
        <td>${escapeHtml(book.author)}</td>
        <td>${escapeHtml(book.genre)}</td>
        <td>${bestSellerBadge}</td>
        <td>
            <div class="action-buttons">
                <button class="btn-update" onclick="openUpdateModal(${book.id}, '${escapeHtml(book.title)}', '${escapeHtml(book.author)}', '${escapeHtml(book.genre)}', ${book.best_selling})">Update</button>
                <button class="btn-delete" onclick="deleteBook(${book.id})">Delete</button>
            </div>
        </td>
    `;
    
    // Add animation delay for staggered effect
    row.style.animationDelay = `${index * 0.1}s`;
    
    return row;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Add some interactive enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to form inputs
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // Add click effect to submit button
    submitBtn.addEventListener('click', function(e) {
        // Create ripple effect
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .submit-btn {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .form-group.focused label {
        color: #667eea;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
`;
document.head.appendChild(style);



// Modal elements
const updateModal = document.getElementById('updateModal');
const updateForm = document.getElementById('updateBookForm');
const closeModalBtn = document.getElementById('closeModal');
const cancelUpdateBtn = document.getElementById('cancelUpdate');

// Update form elements
const updateBookId = document.getElementById('updateBookId');
const updateTitle = document.getElementById('updateTitle');
const updateAuthor = document.getElementById('updateAuthor');
const updateGenre = document.getElementById('updateGenre');
const updateBestSelling = document.getElementById('updateBestSelling');

// Delete book function
async function deleteBook(bookId) {
    if (!confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
        return;
    }
    
    const deleteBtn = document.querySelector(`button[onclick="deleteBook(${bookId})"]`);
    if (deleteBtn) {
        deleteBtn.classList.add('loading');
        deleteBtn.disabled = true;
    }
    
    try {
        const response = await fetch(DELETE_BOOK_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: bookId })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Book deleted successfully!', 'success');
            loadBooks(); // Reload the books table
        } else {
            showMessage(result.message || 'Error deleting book', 'error');
        }
    } catch (error) {
        console.error("Error:", error);
        // Removed network error message as per user request
    } finally {
        if (deleteBtn) {
            deleteBtn.classList.remove("loading");
            deleteBtn.disabled = false;
        }
    }
}

// Open update modal
function openUpdateModal(bookId, title, author, genre, bestSelling) {
    updateBookId.value = bookId;
    updateTitle.value = title;
    updateAuthor.value = author;
    updateGenre.value = genre;
    updateBestSelling.checked = bestSelling;
    
    updateModal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

// Close update modal
function closeUpdateModal() {
    updateModal.classList.remove('show');
    document.body.style.overflow = 'auto'; // Restore scrolling
    updateForm.reset();
}

// Modal event listeners
closeModalBtn.addEventListener('click', closeUpdateModal);
cancelUpdateBtn.addEventListener('click', closeUpdateModal);

// Close modal when clicking outside
updateModal.addEventListener('click', function(e) {
    if (e.target === updateModal) {
        closeUpdateModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && updateModal.classList.contains('show')) {
        closeUpdateModal();
    }
});

// Update form submission
updateForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(updateForm);
    const bookData = {
        id: parseInt(updateBookId.value),
        title: formData.get('title').trim(),
        author: formData.get('author').trim(),
        genre: formData.get('genre'),
        best_selling: formData.get('best_selling') === 'on'
    };
    
    // Validate form data
    if (!validateUpdateFormData(bookData)) {
        return;
    }
    
    const saveBtn = updateForm.querySelector('.btn-save');
    saveBtn.classList.add('loading');
    saveBtn.disabled = true;
    
    try {
        const response = await fetch(UPDATE_BOOK_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(bookData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Book updated successfully!', 'success');
            closeUpdateModal();
            loadBooks(); // Reload the books table
        } else {
            showMessage(result.message || 'Error updating book', 'error');
        }
    } catch (error) {
        console.error("Error:", error);
        // Removed network error message as per user request
    } finally {
        saveBtn.classList.remove("loading");
        saveBtn.disabled = false;
    }
});

// Validate update form data
function validateUpdateFormData(data) {
    if (!data.title) {
        showMessage('Title is required', 'error');
        return false;
    }
    
    if (!data.author) {
        showMessage('Author is required', 'error');
        return false;
    }
    
    if (!data.genre) {
        showMessage('Please select a genre', 'error');
        return false;
    }
    
    return true;
}

