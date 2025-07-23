document.addEventListener('DOMContentLoaded', () => {
  // Helper: get float or 0
  const getFloat = (id) => parseFloat(document.getElementById(id)?.value) || 0;

  function calculate() {
    // Inputs (user inputs)
    const price_yen = getFloat('price');             // E7
    const conversion_rate = getFloat('conversion_rate') || 0.032;  // D95
    const shipping_price_yen = getFloat('shipping_price'); // E9
    const additional_price_yen = getFloat('additional_price'); // E10
    const quantity = getFloat('quantity');            // C9
    const carton_width = getFloat('carton_width');    // C10
    const carton_height = getFloat('carton_height');  // C11
    const carton_length = getFloat('carton_length');  // C12
    const no_of_carton = getFloat('no_of_carton');    // C14
    const weight_carton = getFloat('weight_carton');  // G11

    // Calculations
    // Total Price (Yen)
    const total_price_yen = (price_yen * quantity) + shipping_price_yen + additional_price_yen; // E11

    // Price RM
    const price_rm = price_yen / conversion_rate;

    // Total Price RM
    const total_price_rm = total_price_yen / conversion_rate;

    // 50% Deposit Price (Yen)
    const deposit_50_yen = total_price_yen / 2;

    // 50% Deposit Price (RM)
    const deposit_50_rm = deposit_50_yen / conversion_rate;

    // CBM per carton
    const cbm_carton = (carton_width * carton_height * carton_length) / 1000000;

    // Vm per carton
    const vm_carton = (carton_width * carton_height * carton_length) / 5000;

    // Total Vm
    const total_vm = vm_carton * no_of_carton;

    // Total Weight
    const total_weight = weight_carton * no_of_carton;

    // SG Tax 9%
    const sg_tax = (total_price_rm / 100) * 9;

    // Supplier splits (yen)
    const supplier_1st_yen = total_price_yen / 2;
    const supplier_2nd_yen = total_price_yen / 2;

    // Update fields (both visible and hidden)
    const fields = [
      {id: 'price_rm', value: price_rm.toFixed(6)},              // Price RM (auto calc)
      {id: 'total_price_yen', value: total_price_yen.toFixed(6)},
      {id: 'total_price_rm', value: total_price_rm.toFixed(6)},
      {id: 'deposit_50_yen', value: deposit_50_yen.toFixed(6)},
      {id: 'deposit_50_rm', value: deposit_50_rm.toFixed(6)},
      {id: 'cbm_carton', value: cbm_carton.toFixed(6)},
      {id: 'vm_carton', value: vm_carton.toFixed(6)},
      {id: 'total_vm', value: total_vm.toFixed(6)},
      {id: 'total_weight', value: total_weight.toFixed(6)},
      {id: 'sg_tax', value: sg_tax.toFixed(6)},
      {id: 'supplier_1st', value: supplier_1st_yen.toFixed(6)},
      {id: 'supplier_2nd', value: supplier_2nd_yen.toFixed(6)},
    ];

    fields.forEach(({id, value}) => {
      const visible = document.getElementById(id);
      const hidden = document.getElementById(id + '_hidden');
      if (visible) visible.value = value;
      if (hidden) hidden.value = value;
    });
  }

  // Input IDs to watch for changes
  const inputsToWatch = [
    'price', 'conversion_rate', 'shipping_price', 'additional_price',
    'quantity', 'carton_width', 'carton_height', 'carton_length',
    'no_of_carton', 'weight_carton'
  ];

  inputsToWatch.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', calculate);
  });

  // Initial calculation
  calculate();
});
