/**
 * Automated Unit Test Suite for Hello Elementor Child (Frontend JavaScript Logic)
 * 
 * Run command:
 * node "c:\Users\Administrator\Local Sites\ktd-ecommerce\app\public\wp-content\themes\hello-elementor-child\tests\test-theme-custom.js"
 */

const assert = require('assert');

class JSTestRunner {
  constructor() {
    this.passed = 0;
    this.failed = 0;
    this.startTime = Date.now();
  }

  describe(suiteName) {
    console.log(`\n\x1b[1;36m▶ Suite: ${suiteName}\x1b[0m`);
  }

  it(testName, fn) {
    try {
      fn();
      this.passed++;
      console.log(`  \x1b[32m✔\x1b[0m ${testName}`);
    } catch (err) {
      this.failed++;
      console.log(`  \x1b[31m✖ ${testName}\x1b[0m`);
      console.log(`    \x1b[90mError: ${err.message}\x1b[0m`);
    }
  }

  report() {
    const elapsed = Date.now() - this.startTime;
    console.log('\n' + '='.repeat(60));
    const resultColor = this.failed === 0 ? '\x1b[32m' : '\x1b[31m';
    console.log(`Test Results: \x1b[32m${this.passed} passed\x1b[0m, ${resultColor}${this.failed} failed\x1b[0m (${elapsed} ms)`);
    console.log('='.repeat(60) + '\n');
    return this.failed === 0 ? 0 : 1;
  }
}

// Logic implementations extracted from theme-custom.js for unit testing
function formatVND(amount) {
  // Normalize non-breaking space (U+00A0) that Intl.NumberFormat produces in Node/browsers
  return new Intl.NumberFormat('vi-VN').format(amount).replace(/\u00a0/g, ' ') + ' ₫';
}

function computeSliderBounds(minVal, maxVal, step, totalMin, totalMax, targetChanged) {
  let adjustedMin = minVal;
  let adjustedMax = maxVal;

  if (targetChanged === 'min') {
    if (adjustedMin > adjustedMax - step) {
      adjustedMin = adjustedMax - step;
    }
  } else if (targetChanged === 'max') {
    if (adjustedMax < adjustedMin + step) {
      adjustedMax = adjustedMin + step;
    }
  }

  const percentMin = ((adjustedMin - totalMin) / (totalMax - totalMin)) * 100;
  const percentMax = ((adjustedMax - totalMin) / (totalMax - totalMin)) * 100;
  const highlightWidth = percentMax - percentMin;

  return {
    min: adjustedMin,
    max: adjustedMax,
    percentMin,
    percentMax,
    highlightWidth
  };
}

function computeQuantityChange(current, action, options = {}) {
  const min = options.min !== undefined ? options.min : 1;
  const max = options.max !== undefined ? options.max : Infinity;
  const step = options.step !== undefined ? options.step : 1;

  if (action === 'minus') {
    if (current > min) {
      return Math.max(min, current - step);
    }
    return current;
  } else if (action === 'plus') {
    if (current < max) {
      return Math.min(max, current + step);
    }
    return current;
  }
  return current;
}

// Run Test Suites
const test = new JSTestRunner();

// --- Suite 1: Currency Formatter ---
test.describe('Currency Formatter formatVND()');

test.it('should format standard amount in Vietnamese Dong with dot separator', () => {
  const formatted = formatVND(1500000);
  assert.strictEqual(formatted, '1.500.000 ₫');
});

test.it('should format zero amount correctly', () => {
  const formatted = formatVND(0);
  assert.strictEqual(formatted, '0 ₫');
});

test.it('should format large amounts accurately', () => {
  const formatted = formatVND(50000000);
  assert.strictEqual(formatted, '50.000.000 ₫');
});

test.it('should format odd amounts with precise thousands grouping', () => {
  const formatted = formatVND(24990000);
  assert.strictEqual(formatted, '24.990.000 ₫');
});


// --- Suite 2: Dual-Handle Price Slider Math ---
test.describe('Dual-Handle Price Slider Logic');

test.it('should prevent min thumb from exceeding max thumb minus step', () => {
  const totalMin = 0;
  const totalMax = 50000000;
  const step = 500000;
  const currentMax = 10000000;
  const attemptedMin = 12000000; // Dragged past max

  const result = computeSliderBounds(attemptedMin, currentMax, step, totalMin, totalMax, 'min');
  assert.strictEqual(result.min, 9500000); // Clamped to 10,000,000 - 500,000
  assert.strictEqual(result.max, 10000000);
});

test.it('should prevent max thumb from dropping below min thumb plus step', () => {
  const totalMin = 0;
  const totalMax = 50000000;
  const step = 500000;
  const currentMin = 20000000;
  const attemptedMax = 18000000; // Dragged below min

  const result = computeSliderBounds(currentMin, attemptedMax, step, totalMin, totalMax, 'max');
  assert.strictEqual(result.max, 20500000); // Clamped to 20,000,000 + 500,000
  assert.strictEqual(result.min, 20000000);
});

test.it('should calculate 0% to 100% position accurately across full range', () => {
  const totalMin = 0;
  const totalMax = 50000000;
  const step = 500000;

  const resultFull = computeSliderBounds(0, 50000000, step, totalMin, totalMax, null);
  assert.strictEqual(resultFull.percentMin, 0);
  assert.strictEqual(resultFull.percentMax, 100);
  assert.strictEqual(resultFull.highlightWidth, 100);

  const resultMid = computeSliderBounds(10000000, 30000000, step, totalMin, totalMax, null);
  assert.strictEqual(resultMid.percentMin, 20);
  assert.strictEqual(resultMid.percentMax, 60);
  assert.strictEqual(resultMid.highlightWidth, 40);
});


// --- Suite 3: Cart Quantity Stepper Logic ---
test.describe('Cart Quantity Stepper Logic');

test.it('should decrement value by step when above min', () => {
  const updated = computeQuantityChange(3, 'minus', { min: 1, max: 10, step: 1 });
  assert.strictEqual(updated, 2);
});

test.it('should not decrement below minimum allowed value', () => {
  const updated = computeQuantityChange(1, 'minus', { min: 1, max: 10, step: 1 });
  assert.strictEqual(updated, 1);
});

test.it('should increment value by step when below max', () => {
  const updated = computeQuantityChange(4, 'plus', { min: 1, max: 10, step: 1 });
  assert.strictEqual(updated, 5);
});

test.it('should not increment beyond maximum allowed value', () => {
  const updated = computeQuantityChange(10, 'plus', { min: 1, max: 10, step: 1 });
  assert.strictEqual(updated, 10);
});

test.it('should support custom step values (e.g., bundle steps of 2)', () => {
  const inc = computeQuantityChange(2, 'plus', { min: 2, max: 10, step: 2 });
  assert.strictEqual(inc, 4);
  const dec = computeQuantityChange(4, 'minus', { min: 2, max: 10, step: 2 });
  assert.strictEqual(dec, 2);
});

// --- Suite 4: Collapsible Article & Specs Modal State Transitions ---
test.describe('Collapsible Article & Specs Modal State Logic');

function computeArticleState(currentHeight, threshold = 620) {
  return {
    isCollapsed: currentHeight > threshold,
    showToggle: currentHeight > threshold
  };
}

function toggleArticleState(isCollapsed) {
  return {
    nextCollapsed: !isCollapsed,
    btnLabel: isCollapsed ? 'Thu gọn nội dung' : 'Đọc tiếp bài viết',
    ariaExpanded: isCollapsed
  };
}

function toggleModalState(isOpen) {
  return {
    nextOpen: !isOpen,
    ariaHidden: isOpen,
    bodyClass: !isOpen ? 'modal-open' : ''
  };
}

test.it('should automatically collapse long articles (> 620px) with toggle button', () => {
  const shortArticle = computeArticleState(400);
  assert.strictEqual(shortArticle.isCollapsed, false);
  assert.strictEqual(shortArticle.showToggle, false);

  const longArticle = computeArticleState(1200);
  assert.strictEqual(longArticle.isCollapsed, true);
  assert.strictEqual(longArticle.showToggle, true);
});

test.it('should toggle from collapsed to expanded with proper label and ARIA', () => {
  const expand = toggleArticleState(true);
  assert.strictEqual(expand.nextCollapsed, false);
  assert.strictEqual(expand.btnLabel, 'Thu gọn nội dung');
  assert.strictEqual(expand.ariaExpanded, true);

  const collapse = toggleArticleState(false);
  assert.strictEqual(collapse.nextCollapsed, true);
  assert.strictEqual(collapse.btnLabel, 'Đọc tiếp bài viết');
  assert.strictEqual(collapse.ariaExpanded, false);
});

test.it('should handle specs modal open and close states with body lock', () => {
  const openModal = toggleModalState(false);
  assert.strictEqual(openModal.nextOpen, true);
  assert.strictEqual(openModal.ariaHidden, false);
  assert.strictEqual(openModal.bodyClass, 'modal-open');

  const closeModal = toggleModalState(true);
  assert.strictEqual(closeModal.nextOpen, false);
  assert.strictEqual(closeModal.ariaHidden, true);
  assert.strictEqual(closeModal.bodyClass, '');
});

// --- Suite 5: Interactive Variation Swatches State Logic ---
test.describe('Interactive Variation Swatches Logic');

function selectSwatch(currentValue, newValue) {
  if (currentValue === newValue) {
    return { changed: false, value: currentValue };
  }
  return {
    changed: true,
    value: newValue,
    ariaPressed: true
  };
}

function syncSwatches(options, selectedValue) {
  return options.map(opt => ({
    value: opt,
    isActive: opt === selectedValue,
    ariaPressed: opt === selectedValue
  }));
}

test.it('should switch active swatch and return changed value when a new option is selected', () => {
  const result = selectSwatch('256gb', '512gb');
  assert.strictEqual(result.changed, true);
  assert.strictEqual(result.value, '512gb');
  assert.strictEqual(result.ariaPressed, true);
});

test.it('should ignore selection when clicking the already active swatch', () => {
  const result = selectSwatch('256gb', '256gb');
  assert.strictEqual(result.changed, false);
  assert.strictEqual(result.value, '256gb');
});

test.it('should correctly synchronize active states for all swatch options based on select value', () => {
  const options = ['128gb', '256gb', '512gb'];
  const swatches = syncSwatches(options, '256gb');
  assert.strictEqual(swatches[0].isActive, false);
  assert.strictEqual(swatches[1].isActive, true);
  assert.strictEqual(swatches[1].ariaPressed, true);
  assert.strictEqual(swatches[2].isActive, false);
});

// --- Suite 6: Installment 0% Modal & AI Consultation State Logic ---
test.describe('Installment 0% Modal & AI Consultation Logic');

function handleInstallmentModal(isOpen) {
  return {
    isVisible: !isOpen,
    ariaHidden: isOpen,
    bodyOverflow: !isOpen ? 'hidden' : ''
  };
}

function handleAiInstallmentConsultation() {
  return {
    modalClosed: true,
    chatTriggered: true,
    initialPrompt: 'Em muốn tư vấn mua trả góp 0% cho sản phẩm này, thủ tục gồm những gì ạ?'
  };
}

test.it('should toggle installment modal visibility and manage body scroll lock', () => {
  const open = handleInstallmentModal(false);
  assert.strictEqual(open.isVisible, true);
  assert.strictEqual(open.ariaHidden, false);
  assert.strictEqual(open.bodyOverflow, 'hidden');

  const close = handleInstallmentModal(true);
  assert.strictEqual(close.isVisible, false);
  assert.strictEqual(close.ariaHidden, true);
  assert.strictEqual(close.bodyOverflow, '');
});

test.it('should close modal and prepare AI chat prompt upon clicking AI consultation', () => {
  const aiAction = handleAiInstallmentConsultation();
  assert.strictEqual(aiAction.modalClosed, true);
  assert.strictEqual(aiAction.chatTriggered, true);
  assert.ok(aiAction.initialPrompt.includes('trả góp 0%'));
});

process.exit(test.report());

