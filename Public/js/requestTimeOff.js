
  const modal = document.querySelector('.modal-overlay');
  const closeBtn = modal.querySelector('.close-btn');

  // Function to open modal (call this function to open the modal)
  function openRequestTimeOffModal() {
    modal.hidden = false;
    // Focus the first interactive element:
    modal.querySelector('select, input, textarea').focus();
  }

  // Close modal on clicking close button
  closeBtn.addEventListener('click', () => {
    modal.hidden = true;
  });

  // Close modal on pressing Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.hidden) {
      modal.hidden = true;
    }
  });
