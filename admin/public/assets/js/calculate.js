document.addEventListener('DOMContentLoaded', () => {
  // Helper: get float or 0
  const getFloat = (id) => parseFloat(document.getElementById(id)?.value) || 0;

  // Main calculate
  function calculateMain() {
    const price_yen = getFloat('price');
    const conversion_rate = getFloat('conversion_rate') || 0.032;
    const shipping_price_yen = getFloat('shipping_price');
    const additional_price_yen = getFloat('additional_price');
    const quantity = getFloat('quantity');
    const carton_width = getFloat('carton_width');
    const carton_height = getFloat('carton_height');
    const carton_length = getFloat('carton_length');
    const no_of_carton = getFloat('no_of_carton');
    const weight_carton = getFloat('weight_carton');

    const total_price_yen = (price_yen * quantity) + shipping_price_yen + additional_price_yen;
    const price_rm = price_yen / conversion_rate;
    const total_price_rm = total_price_yen / conversion_rate;
    const deposit_50_yen = total_price_yen / 2;
    const deposit_50_rm = deposit_50_yen / conversion_rate;
    const cbm_carton = (carton_width * carton_height * carton_length) / 1000000;
    const vm_carton = (carton_width * carton_height * carton_length) / 5000;
    const total_vm = vm_carton * no_of_carton;
    const total_weight = weight_carton * no_of_carton;
    const sg_tax = (total_price_rm / 100) * 9;
    const supplier_1st_yen = total_price_yen / 2;
    const supplier_2nd_yen = total_price_yen / 2;

    // ✅ Get all additional carton total_cbm
    const add_cbm_1 = getFloat('add_carton1_total_cbm');
    const add_cbm_2 = getFloat('add_carton2_total_cbm');
    const add_cbm_3 = getFloat('add_carton3_total_cbm');
    const add_cbm_4 = getFloat('add_carton4_total_cbm');
    const add_cbm_5 = getFloat('add_carton5_total_cbm');
    const add_cbm_6 = getFloat('add_carton6_total_cbm');

    // ✅ Total CBM formula: ((G7*C14)+C24+F24+F33+C33+C42+F42)*1.28
    const total_cbm_main = cbm_carton * no_of_carton;
    const total_cbm = (total_cbm_main + add_cbm_1 + add_cbm_2 + add_cbm_3 + add_cbm_4 + add_cbm_5 + add_cbm_6) * 1.28;

    const fields = [
      {id: 'price_rm', value: price_rm.toFixed(6)},
      {id: 'total_price_yen', value: total_price_yen.toFixed(6)},
      {id: 'total_price_rm', value: total_price_rm.toFixed(6)},
      {id: 'deposit_50_yen', value: deposit_50_yen.toFixed(6)},
      {id: 'deposit_50_rm', value: deposit_50_rm.toFixed(6)},
      {id: 'cbm_carton', value: cbm_carton.toFixed(6)},
      {id: 'total_cbm', value: total_cbm.toFixed(6)}, // ✅ updated total cbm
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

  // Additional cartons calculation
  function calculateAdditionalCartons() {
    for (let i = 1; i <= 6; i++) {
      const w = getFloat(`add_carton${i}_width`);
      const h = getFloat(`add_carton${i}_height`);
      const l = getFloat(`add_carton${i}_length`);
      const n = getFloat(`add_carton${i}_no`);

      const cbmPerCarton = (w * h * l) / 1000000; // m³ per carton
      const totalCbm = cbmPerCarton * n;

      const visible = document.getElementById(`add_carton${i}_total_cbm`);
      const hidden = document.getElementById(`add_carton${i}_total_cbm_hidden`);
      if (visible) visible.value = totalCbm.toFixed(6);
      if (hidden) hidden.value = totalCbm.toFixed(6);
    }
  }

  function calculateAll() {
    calculateAdditionalCartons();
    calculateMain();
  }

  // Watch all relevant inputs
  const mainInputs = [
    'price', 'conversion_rate', 'shipping_price', 'additional_price',
    'quantity', 'carton_width', 'carton_height', 'carton_length',
    'no_of_carton', 'weight_carton'
  ];

  const additionalInputs = [];
  for (let i = 1; i <= 6; i++) {
    additionalInputs.push(`add_carton${i}_width`);
    additionalInputs.push(`add_carton${i}_height`);
    additionalInputs.push(`add_carton${i}_length`);
    additionalInputs.push(`add_carton${i}_no`);
  }

  [...mainInputs, ...additionalInputs].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', calculateAll);
  });

  // Initial calculation
  calculateAll();
});


