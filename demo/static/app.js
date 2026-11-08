// Global Demo State
let currentOrder = null;
let pollTimer = null;
let countdownTimer = null;
let remainingSecs = 900; // 15 mins

function formatCurrency(num) {
  return "Rp " + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function formatCountdown(secs) {
  const m = Math.floor(secs / 60);
  const s = secs % 60;
  return (m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s;
}

function openEnrollModal(courseName, price) {
  currentOrder = {
    courseName: courseName,
    price: price
  };

  document.getElementById("modal-course-title").innerText = courseName;
  document.getElementById("modal-course-price").innerText = formatCurrency(price);

  // Show Step 1, hide others
  document.getElementById("step-form").classList.remove("hidden");
  document.getElementById("step-qris").classList.add("hidden");
  document.getElementById("step-success").classList.add("hidden");

  document.getElementById("payment-modal").classList.remove("hidden");
  lucide.createIcons();
}

function closePaymentModal() {
  clearInterval(pollTimer);
  clearInterval(countdownTimer);
  document.getElementById("payment-modal").classList.add("hidden");
}

async function submitEnrollment() {
  const btn = document.getElementById("btn-create-order");
  btn.disabled = true;
  btn.innerHTML = `<span class="animate-spin mr-2">⏳</span> Membuat Tagihan QRIS...`;

  const studentName = document.getElementById("input-student-name").value.trim();
  const studentEmail = document.getElementById("input-student-email").value.trim();
  const studentPhone = document.getElementById("input-student-phone").value.trim();

  try {
    const res = await fetch("/api/orders/create", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        course_name: currentOrder.courseName,
        price: currentOrder.price,
        student_name: studentName,
        student_email: studentEmail,
        student_phone: studentPhone
      })
    });
    const data = await res.json();

    if (data.success && data.order) {
      currentOrder = data.order;
      showQRISPaymentScreen(data.order);
    }
  } catch (err) {
    alert("Gagal membuat pesanan. Pastikan server lokal berjalan.");
  } finally {
    btn.disabled = false;
    btn.innerHTML = `<span>Lanjut ke Pembayaran QRIS</span> <i data-lucide="arrow-right" class="w-4 h-4"></i>`;
    lucide.createIcons();
  }
}

function showQRISPaymentScreen(order) {
  document.getElementById("step-form").classList.add("hidden");
  document.getElementById("step-qris").classList.remove("hidden");
  document.getElementById("step-success").classList.add("hidden");

  document.getElementById("qris-order-id").innerText = `#HAOHAO-${order.order_id}`;
  document.getElementById("qris-total-amount").innerText = formatCurrency(order.price);
  document.getElementById("qris-img").src = order.qr_url;

  // Start 15 mins Countdown
  remainingSecs = 900;
  clearInterval(countdownTimer);
  countdownTimer = setInterval(() => {
    remainingSecs--;
    if (remainingSecs <= 0) {
      clearInterval(countdownTimer);
      clearInterval(pollTimer);
      document.getElementById("qris-countdown").innerText = "00:00 (KADALUARSA)";
      document.getElementById("qris-status-box").innerHTML = `<span class="text-rose-600 font-bold">⚠️ Waktu pembayaran telah habis.</span>`;
      return;
    }
    document.getElementById("qris-countdown").innerText = formatCountdown(remainingSecs);
  }, 1000);

  // Start Real-time Polling
  clearInterval(pollTimer);
  pollTimer = setInterval(pollOrderStatus, 2500);
  lucide.createIcons();
}

async function pollOrderStatus() {
  if (!currentOrder || !currentOrder.order_id) return;

  try {
    const res = await fetch(`/api/orders/status/${currentOrder.order_id}`);
    const data = await res.json();

    if (data.success && data.is_paid) {
      clearInterval(pollTimer);
      clearInterval(countdownTimer);
      showSuccessScreen(data.order);
    }
  } catch (err) {
    // Retry on next interval
  }
}

// Trigger simulated Webhook from the demo console
async function triggerSimulatedPayment(issuer) {
  if (!currentOrder || !currentOrder.order_id) return;

  try {
    const res = await fetch("/api/webhook/simulate", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        order_id: currentOrder.order_id,
        issuer: issuer
      })
    });
    const data = await res.json();

    if (data.success) {
      // Trigger instant check
      pollOrderStatus();
    }
  } catch (err) {
    alert("Gagal memicu webhook simulasi.");
  }
}

function showSuccessScreen(order) {
  document.getElementById("step-qris").classList.add("hidden");
  document.getElementById("step-success").classList.remove("hidden");

  document.getElementById("success-issuer").innerText = `QRIS (${order.issuer || "m-Banking / E-Wallet"})`;
  document.getElementById("success-time").innerText = order.paid_at || new Date().toLocaleString();

  // Celebration Confetti
  if (typeof confetti === "function") {
    confetti({ particleCount: 120, spread: 80, origin: { y: 0.6 } });
  }
  lucide.createIcons();
}
