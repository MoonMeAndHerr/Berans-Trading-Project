document.addEventListener('DOMContentLoaded', () => {
  // Helper: get float or 0
  const getFloat = (id) => parseFloat(document.getElementById(id)?.value) || 0;

  // Shipping prices data (simulate dynamic DB prices)
  // These would normally be fetched or embedded dynamically, 
  // but here hardcoded for demo based on your price_shipping table.
  const priceData = {
    // shipping_code: { price_per_cbm_normal, price_per_cbm_sensitive, sg_cbm_normal, sg_cbm_sensitive, price_kg_normal, price_kg_sensitive, sg_kg_normal, sg_kg_sensitive, ocool_cbm_normal, ocool_cbm_sensitive }
    'M1':  { price_cbm: 380, sensitive_cbm: 380, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 17, sensitive_kg: 19, sg_kg: 0, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'M2':  { price_cbm: 380, sensitive_cbm: 380, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 17, sensitive_kg: 19, sg_kg: 0, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'S1':  { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 285, sg_sensitive_cbm: 285, price_kg: 0, sensitive_kg: 0, sg_kg: 24, sg_sensitive_kg: 24, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'S2':  { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 285, sg_sensitive_cbm: 285, price_kg: 0, sensitive_kg: 0, sg_kg: 24, sg_sensitive_kg: 24, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'OCSG1': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 0, sensitive_kg: 0, sg_kg: 0, sg_sensitive_kg: 0, ocool_cbm: 255, ocool_sensitive_cbm: 0 },
    'OCSG2': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 0, sensitive_kg: 0, sg_kg: 0, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 275 },
    'M3a': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 17, sensitive_kg: 0, sg_kg: 0, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'M3b': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 17, sensitive_kg: 0, sg_kg: 0, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'M4a': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 0, sensitive_kg: 19, sg_kg: 0, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'M4b': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 0, sensitive_kg: 19, sg_kg: 0, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'S3a': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 0, sensitive_kg: 0, sg_kg: 24, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'S3b': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 0, sensitive_kg: 0, sg_kg: 24, sg_sensitive_kg: 0, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'S4a': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 0, sensitive_kg: 0, sg_kg: 0, sg_sensitive_kg: 24, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
    'S4b': { price_cbm: 0, sensitive_cbm: 0, sg_cbm: 0, sg_sensitive_cbm: 0, price_kg: 0, sensitive_kg: 0, sg_kg: 0, sg_sensitive_kg: 24, ocool_cbm: 0, ocool_sensitive_cbm: 0 },
  };

  // Calculate shipping totals based on selected shipping_code and inputs
  function calculateShippingTotals() {
    const shippingCode = document.getElementById('shipping_code')?.value || '';
    const cbm = getFloat('total_cbm');    // total CBM from cartons + additionals
    const vm = getFloat('total_vm');      // total volume measure
    const kg = getFloat('total_weight');  // total weight in kg

    let price_total_sea_shipping = 0;
    let price_total_air_shipping_vm = 0;
    let price_total_air_shipping_kg = 0;

    const priceEntry = priceData[shippingCode];
    if (!priceEntry) {
      // No valid shipping code selected or no data
      updateShippingFields(0, 0, 0);
      return;
    }

    // Determine calculation type by shipping code pattern
    // Sea shipping (CBM based)
    if (['M1', 'M2', 'S1', 'S2', 'OCSG1', 'OCSG2'].includes(shippingCode)) {
      if (shippingCode === 'M1') price_total_sea_shipping = cbm * priceEntry.price_cbm;
      else if (shippingCode === 'M2') price_total_sea_shipping = cbm * priceEntry.sensitive_cbm;
      else if (shippingCode === 'S1') price_total_sea_shipping = cbm * priceEntry.sg_cbm;
      else if (shippingCode === 'S2') price_total_sea_shipping = cbm * priceEntry.sg_sensitive_cbm;
      else if (shippingCode === 'OCSG1') price_total_sea_shipping = cbm * priceEntry.ocool_cbm;
      else if (shippingCode === 'OCSG2') price_total_sea_shipping = cbm * priceEntry.ocool_sensitive_cbm;
    }

    // Air shipping VM based
    if (['M3a', 'M4a', 'S3a', 'S4a'].includes(shippingCode)) {
      if (shippingCode === 'M3a') price_total_air_shipping_vm = vm * priceEntry.price_kg;
      else if (shippingCode === 'M4a') price_total_air_shipping_vm = vm * priceEntry.sensitive_kg;
      else if (shippingCode === 'S3a') price_total_air_shipping_vm = vm * priceEntry.sg_kg;
      else if (shippingCode === 'S4a') price_total_air_shipping_vm = vm * priceEntry.sg_sensitive_kg;
    }

    // Air shipping KG based
    if (['M3b', 'M4b', 'S3b', 'S4b'].includes(shippingCode)) {
      if (shippingCode === 'M3b') price_total_air_shipping_kg = kg * priceEntry.price_kg;
      else if (shippingCode === 'M4b') price_total_air_shipping_kg = kg * priceEntry.sensitive_kg;
      else if (shippingCode === 'S3b') price_total_air_shipping_kg = kg * priceEntry.sg_kg;
      else if (shippingCode === 'S4b') price_total_air_shipping_kg = kg * priceEntry.sg_sensitive_kg;
    }

    updateShippingFields(price_total_sea_shipping, price_total_air_shipping_vm, price_total_air_shipping_kg);
  }

  // Update visible + hidden shipping total fields
  function updateShippingFields(sea, air_vm, air_kg) {
    const seaEl = document.getElementById('price_total_sea_shipping');
    const seaHidden = document.getElementById('price_total_sea_shipping_hidden');
    const airVmEl = document.getElementById('price_total_air_shipping_vm');
    const airVmHidden = document.getElementById('price_total_air_shipping_vm_hidden');
    const airKgEl = document.getElementById('price_total_air_shipping_kg');
    const airKgHidden = document.getElementById('price_total_air_shipping_kg_hidden');

    if (seaEl) seaEl.value = sea.toFixed(2);
    if (seaHidden) seaHidden.value = sea.toFixed(2);
    if (airVmEl) airVmEl.value = air_vm.toFixed(2);
    if (airVmHidden) airVmHidden.value = air_vm.toFixed(2);
    if (airKgEl) airKgEl.value = air_kg.toFixed(2);
    if (airKgHidden) airKgHidden.value = air_kg.toFixed(2);
  }

  // Main calculate function you provided
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

    // Get additional cartons CBM
    const add_cbm_1 = getFloat('add_carton1_total_cbm');
    const add_cbm_2 = getFloat('add_carton2_total_cbm');
    const add_cbm_3 = getFloat('add_carton3_total_cbm');
    const add_cbm_4 = getFloat('add_carton4_total_cbm');
    const add_cbm_5 = getFloat('add_carton5_total_cbm');
    const add_cbm_6 = getFloat('add_carton6_total_cbm');

    const total_cbm_main = cbm_carton * no_of_carton;
    const total_cbm = (total_cbm_main + add_cbm_1 + add_cbm_2 + add_cbm_3 + add_cbm_4 + add_cbm_5 + add_cbm_6) * 1.28;

    const fields = [
      {id: 'price_rm', value: price_rm.toFixed(6)},
      {id: 'total_price_yen', value: total_price_yen.toFixed(6)},
      {id: 'total_price_rm', value: total_price_rm.toFixed(6)},
      {id: 'deposit_50_yen', value: deposit_50_yen.toFixed(6)},
      {id: 'deposit_50_rm', value: deposit_50_rm.toFixed(6)},
      {id: 'cbm_carton', value: cbm_carton.toFixed(6)},
      {id: 'total_cbm', value: total_cbm.toFixed(6)},
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

    // After main calculation, calculate shipping totals
    calculateShippingTotals();
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
    'no_of_carton', 'weight_carton', 'shipping_code'  // Added shipping_code listener
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
