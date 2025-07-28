document.addEventListener('DOMContentLoaded', () => {
  // Helper: get float or 0
  const getFloat = (id) => parseFloat(document.getElementById(id)?.value) || 0;

  // Convert database shipping prices to usable format
  const priceData = {};
  if (typeof dbShippingPrices !== 'undefined') {
    dbShippingPrices.forEach(row => {
      priceData[row.shipping_code] = {
        price_cbm: row.price_cbm_normal_goods,
        sensitive_cbm: row.price_cbm_sensitive_goods,
        sg_cbm: row.sg_price_cbm_normal_goods,
        sg_sensitive_cbm: row.sg_price_cbm_sensitive_goods,
        price_kg: row.price_kg_normal_goods,
        sensitive_kg: row.price_kg_sensitive_goods,
        sg_kg: row.sg_price_kg_normal_goods,
        sg_sensitive_kg: row.sg_price_kg_sensitive_goods,
        ocool_cbm: row.ocool_sg_price_cbm_normal_goods,
        ocool_sensitive_cbm: row.ocool_sg_price_cbm_sensitive_goods
      };
    });
  }

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

  // Main calculate function
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

  // Final Selling Unit & Profit Calculations
  const finalSellingUnitInput = document.getElementById('final_selling_unit');
  const finalSellingUnit = parseFloat(finalSellingUnitInput?.value) || 0;

  const priceTotalSea = getFloat('price_total_sea_shipping');
  const priceTotalAirVM = getFloat('price_total_air_shipping_vm');
  const priceTotalAirKG = getFloat('price_total_air_shipping_kg');
  const sgTaxValue = getFloat('sg_tax');
  const finalTotalPrice = total_price_rm + priceTotalSea + priceTotalAirVM + priceTotalAirKG + sgTaxValue;

  const finalUnitPrice = quantity > 0 ? (finalTotalPrice / quantity) : 0;

  const updateField = (id, val) => {
    const el = document.getElementById(id);
    const hidden = document.getElementById(id + '_hidden');
    if (el) el.value = val.toFixed(2);
    if (hidden) hidden.value = val.toFixed(2);
  };

  // Always update these two
  updateField('final_total_price', finalTotalPrice);
  updateField('final_unit_price', finalUnitPrice);

  // ✅ ✅ Add Customer 1st and 2nd RM Split (UPDATED)
  const finalSellingTotal = finalSellingUnit * quantity;
  const customer_1st_rm = finalSellingTotal / 2;
  const customer_2nd_rm = finalSellingTotal / 2;

  updateField('customer_1st', customer_1st_rm);
  updateField('customer_2nd', customer_2nd_rm);

  if (finalSellingUnit > 0) {
    const finalProfitPerUnit = finalSellingUnit - finalUnitPrice;
    const finalTotalProfit = finalSellingTotal - finalTotalPrice;
    const finalProfitPercent = finalTotalPrice > 0 ? (finalTotalProfit / finalTotalPrice) * 100 : 0;
    const zakatValue = finalTotalProfit * 0.10;

    updateField('final_selling_total', finalSellingTotal);
    updateField('final_profit_per_unit', finalProfitPerUnit);
    updateField('final_total_profit', finalTotalProfit);
    updateField('final_profit_percent', finalProfitPercent);
    updateField('zakat', zakatValue);
  } else {
    updateField('final_selling_total', 0);
    updateField('final_profit_per_unit', 0);
    updateField('final_total_profit', 0);
    updateField('final_profit_percent', 0);
    updateField('zakat', 0);
  }
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
    'no_of_carton', 'weight_carton', 'shipping_code', 'final_selling_unit'
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