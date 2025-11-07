// Global variables
let selectedSeats = [];
const farePerSeat = parseFloat(document.getElementById('farePerSeat')?.textContent || '0');

// Seat selection functionality
function initializeSeatMap() {
    const seatMap = document.getElementById('seatMap');
    if (!seatMap) return;

    seatMap.addEventListener('click', (e) => {
        if (e.target.classList.contains('seat') && !e.target.classList.contains('booked')) {
            const seat = e.target.dataset.seat;
            
            if (e.target.classList.contains('selected')) {
                // Deselect seat
                e.target.classList.remove('selected');
                selectedSeats = selectedSeats.filter(s => s !== seat);
            } else {
                // Select seat
                e.target.classList.add('selected');
                selectedSeats.push(seat);
            }

            updateBookingSummary();
        }
    });
}

// Update booking summary
function updateBookingSummary() {
    const selectedSeatsText = document.getElementById('selectedSeatsText');
    const selectedSeatsInput = document.getElementById('selectedSeatsInput');
    const totalAmount = document.getElementById('totalAmount');
    const bookButton = document.getElementById('bookButton');

    if (selectedSeatsText && selectedSeatsInput && totalAmount && bookButton) {
        selectedSeatsText.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None';
        selectedSeatsInput.value = selectedSeats.join(',');
        totalAmount.textContent = (selectedSeats.length * farePerSeat).toFixed(2);
        bookButton.disabled = selectedSeats.length === 0;
    }
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required]');

    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('border-red-500');
        } else {
            input.classList.remove('border-red-500');
        }
    });

    return isValid;
}

// Modal functionality
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Date validation
function validateDate(dateInput) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const selectedDate = new Date(dateInput.value);
    selectedDate.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        dateInput.value = today.toISOString().split('T')[0];
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize seat map if on booking page
    initializeSeatMap();

    // Add date validation to date inputs
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.min = new Date().toISOString().split('T')[0];
        input.addEventListener('change', () => validateDate(input));
    });

    // Initialize form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!validateForm(form.id)) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Add modal close listeners
    const modalCloseButtons = document.querySelectorAll('[data-modal-close]');
    modalCloseButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.dataset.modalClose;
            closeModal(modalId);
        });
    });
});

// AJAX functions for dynamic updates
function updateSeatAvailability(busId, date) {
    fetch(`/backend/get-available-seats.php?bus_id=${busId}&date=${date}`)
        .then(response => response.json())
        .then(data => {
            const availabilityElement = document.getElementById(`seats-${busId}`);
            if (availabilityElement) {
                availabilityElement.textContent = `${data.available} seats available`;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Success message animation
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded shadow-lg success-animation';
    successDiv.textContent = message;
    document.body.appendChild(successDiv);

    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}