// Tab Functionality
const tabs = document.querySelectorAll('.tab');
tabs.forEach(tab => {
    tab.addEventListener('click', function() {
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

// Time Filter Functionality
const timeFilters = document.querySelectorAll('.time-filter');
timeFilters.forEach(filter => {
    filter.addEventListener('click', function() {
        timeFilters.forEach(f => f.classList.remove('active'));
        this.classList.add('active');
    });
});

// Date Range Selection
const startDateInput = document.querySelector('.start-date');
const endDateInput = document.querySelector('.end-date');

startDateInput.addEventListener('change', function() {
    console.log('Start date selected:', this.value);
});

endDateInput.addEventListener('change', function() {
    console.log('End date selected:', this.value);
});
