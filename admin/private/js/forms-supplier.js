const supplierSelect = document.getElementById('supplierSelectUpdate');
  const updateForm = document.getElementById('updateForm');
  const idField = document.getElementById('update_supplier_id');
  const nameField = document.getElementById('update_supplier_name');
  const contactField = document.getElementById('update_supplier_contact');
  const phoneField = document.getElementById('update_supplier_phone');
  const emailField = document.getElementById('update_supplier_email');
  const addressField = document.getElementById('update_supplier_address');
  const notesField = document.getElementById('update_supplier_notes');

  supplierSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];

    if (!this.value) {
      // no selection
      updateForm.style.display = 'none';
      idField.value = '';
      nameField.value = '';
      contactField.value = '';
      phoneField.value = '';
      emailField.value = '';
      addressField.value = '';
      notesField.value = '';
      return;
    }

    // show form and fill values
    updateForm.style.display = 'block';
    idField.value = this.value;
    nameField.value = selectedOption.dataset.name || '';
    contactField.value = selectedOption.dataset.contact || '';
    phoneField.value = selectedOption.dataset.phone || '';
    emailField.value = selectedOption.dataset.email || '';
    addressField.value = selectedOption.dataset.address || '';
    notesField.value = selectedOption.dataset.notes || '';
  });

  const deleteSelect = document.getElementById('deleteSupplierSelect');
  const deleteId = document.getElementById('delete_supplier_id');
  const deleteName = document.getElementById('delete_supplier_name');
  const deleteContact = document.getElementById('delete_supplier_contact');
  const deletePhone = document.getElementById('delete_supplier_phone');
  const deleteEmail = document.getElementById('delete_supplier_email');
  const deleteAddress = document.getElementById('delete_supplier_address');
  const deleteNotes = document.getElementById('delete_supplier_notes');

  deleteSelect.addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];

    if (!this.value) {
      // Clear fields when nothing is selected
      deleteId.value = '';
      deleteName.value = '';
      deleteContact.value = '';
      deletePhone.value = '';
      deleteEmail.value = '';
      deleteAddress.value = '';
      deleteNotes.value = '';
      return;
    }

    // Fill the fields with selected supplier data
    deleteId.value = this.value;
    deleteName.value = selectedOption.dataset.name || '';
    deleteContact.value = selectedOption.dataset.contact || '';
    deletePhone.value = selectedOption.dataset.phone || '';
    deleteEmail.value = selectedOption.dataset.email || '';
    deleteAddress.value = selectedOption.dataset.address || '';
    deleteNotes.value = selectedOption.dataset.notes || '';
  });