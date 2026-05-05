<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawikanCare</title>
  <link rel="stylesheet" href="{{ secure_asset('css/style.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <style>
    .leaflet-container .pin-div {
      width: 22px;
      height: 22px;
      background: #007bff;
      border-radius: 50% 50% 50% 0;
      transform: rotate(-45deg);
      border: 2px solid #fff;
      box-shadow: 0 1px 2px rgba(0,0,0,0.3);
      cursor: pointer;
      display:block;
    }
    .pin-tooltip {
      position: absolute;
      left: 26px;
      top: -6px;
      background: rgba(0,0,0,0.8);
      color: #fff;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      white-space: nowrap;
      opacity: 0;
      transition: opacity .12s;
      pointer-events: none;
    }
    .leaflet-marker-icon .pin-label { display:none; }
    .map-wrapper { position: relative; }
    #pins { display: none; }
  </style>
</head>
<body>
  <div class="bg-wrap" aria-hidden="true">
    <img src="/turtle-bg.png" class="bg-image" alt="">
    <div class="bg-dim"></div>
  </div>

  <aside id="app">
    <div class="container">
      <!-- HEADER -->
      <header class="glass header-glass">
        <div class="header-left">
          <h1 class="brand">PAWIKANCARE</h1>
        </div>
        <div class="header-right">
          @auth
          <span class="welcome">Welcome, {{ auth()->user()->name }} !</span>
          <form method="POST" action="{{ route('logout') }}" class="inline-form">
            @csrf
            <button class="btn primary" type="submit">Logout</button>
          </form>
          @endauth
        </div>
      </header>

      <!-- STATS -->
      <section class="glass stats-glass">
        <div class="stats-row">
          <div class="stats-card">
            <p class="stat-label">Total Nests</p>
            <p class="stat-value">{{ $totalNests ?? 0 }}</p>
            <p class="stat-sub">Total records logged</p>
          </div>
          <div class="stats-card">
            <p class="stat-label">Total Eggs</p>
            <p class="stat-value">{{ $totalEggs ?? 0 }}</p>
            <p class="stat-sub">All eggs recorded</p>
          </div>
          


        </div>
      </section>

      <!-- MAP -->
      <section class="glass map-glass">
        <div class="map-header">
          <h2 class="section-title">Beach Map</h2>
          <p class="section-sub">Click to drop a pin. Click a Pin to Edit or View Summary.</p>
        </div>
        <div class="map-wrapper">
          <div id="beachMap" style="height: 500px; width: 100%; border-radius: 12px;"></div>
          <div id="pins"></div>
        </div>
      </section>

      <!-- CHARTS -->
      <section class="glass charts-glass">
    <div class="chart-header">
      <h2 class="section-title">Charts</h2>
    </div>

    <!-- Unified Month-Year Filter -->
    <div style="display:flex; gap:12px; margin-bottom:20px;">
    <select id="yearFilter" class="filter-select">
        <option value="" selected disabled>Select Year</option>
    </select>

    <select id="monthFilter" class="filter-select">
        <option value="" selected disabled>Select Month</option>
        <option value="1">January</option>
        <option value="2">February</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
    </select>
</div>



    <div class="charts-grid">
      <div class="chart-box glass">
        <h3>Threat Data per Month</h3>
        <canvas id="threatChart"></canvas>
      </div>
      <div class="chart-box glass">
        <h3>Egg Count per Month</h3>
        <canvas id="eggChart"></canvas>
      </div>
      <div class="chart-box glass">
        <h3>Hatching & Release per Month</h3>
        <canvas id="hatchBarChart"></canvas>
      </div>
      <div class="chart-box glass">
        <h3>Nest Count per Month</h3>
        <canvas id="nestBarChart"></canvas>
      </div>
    </div>
    <div class="chart-box glass">
      <h3>Hatched vs Unhatched</h3>
      <canvas id="pieChart"></canvas>
    </div>
</section>

    </div>
  </aside>

  <!-- POPUP FORM -->
  <div id="dataForm" class="popup glass popup-glass hidden">
    <form id="nestForm" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" id="coord_x" name="x">
      <input type="hidden" id="coord_y" name="y">
      <input type="hidden" id="coord_lat" name="lat">
      <input type="hidden" id="coord_lng" name="lng">

      <h3 class="popup-title">Nest Discovery</h3>
      <p><strong>User:</strong> {{ auth()->user()->name }}</p>
      <label>Date:</label>
      <input type="date" id="nestDate" name="nest_date">
      <label>Turtle Species:</label>
      <select id="species" name="species">
        <option value="">Select species</option>
        <option value="Green Sea Turtle (Chelonia mydas)">Green Sea Turtle</option>
        <option value="Olive Ridley Turtle (Lepidochelys olivacea)">Olive Ridley Turtle</option>
        <option value="Loggerhead Turtle (Caretta caretta)">Loggerhead Turtle</option>
        <option value="Leatherback Turtle (Dermochelys coriacea)">Leatherback Turtle</option>
      </select>
      <label>Egg Count:</label>
      <input type="number" id="eggCount" name="egg_count">
      <label>Upload Photo:</label>
      <input type="file" id="nestPhoto" name="nest_photo">

      <hr>
      <h4>Incubation Logs</h4>
      <div id="incubationLogs"></div>
      <button type="button" id="addLogBtn" class="btn secondary">Add Log</button>

      <hr>
      <h4>Threat Report</h4>
      <label>Date:</label>
      <input type="date" id="threatDate" name="threat_date">
      <label>Threat Type:</label>
      <select id="threatType" name="threat_type">
        <option value="">Select Threat Type</option>
        <option value="Illegal Harvesting">Illegal Harvesting</option>
        <option value="Habitat Destruction">Habitat Destruction</option>
        <option value="Temperature Extremes">Temperature Extremes</option>
        <option value="Predation">Predation</option>
        <option value="Pollution">Pollution</option>
        <option value="Beach Activities and Traffic">Beach Activities and Traffic</option>
        <option value="Light Pollution">Light Pollution</option>
      </select>
      <label>Upload a Photo:</label>
      <input type="file" id="threatPhoto" name="threat_photo">

      <hr>
      <h4>Hatching & Release</h4>
      <label>Date Hatched:</label>
      <input type="date" id="hatchDate" name="hatch_date">
      <label>Eggs Hatched:</label>
      <div class="number-input">
        <button type="button" class="btn ghost" id="hatchedDown">-</button>
        <input type="number" id="eggHatched" name="egg_hatched" value="0">
        <button type="button" class="btn ghost" id="hatchedUp">+</button>
      </div>
      <label>Eggs Not Hatched:</label>
      <div class="number-input">
        <button type="button" class="btn ghost" id="unhatchedDown">-</button>
        <input type="number" id="eggUnhatched" name="egg_unhatched" value="0">
        <button type="button" class="btn ghost" id="unhatchedUp">+</button>
      </div>
      <label>Upload a Photo:</label>
      <input type="file" id="hatchPhoto" name="hatch_photo">
      <label>Description:</label>
      <textarea id="hatchNotes" name="hatch_notes"></textarea>

      <div class="popup-actions">
        <button type="button" class="btn secondary" id="backFromForm">Back</button>
        <button type="button" class="btn primary" id="saveData">Save</button>
      </div>
    </form>
  </div>

  <!-- SUMMARY POPUP -->
  <div id="summaryPopup" class="popup glass popup-glass hidden">
    <div id="summaryContent" class="summary-content"></div>
    <div class="popup-actions">
      <button id="deletePin" class="btn secondary">Delete</button>
      <button id="exportPinPdf" class="btn primary" 
  style="{{ auth()->user()->role !== 'admin' ? 'display:none;' : '' }}">
  Export PDF
</button>

      <button id="backFromSummary" class="btn secondary">Back</button>
      <button id="newNestReport" class="btn primary">New Nest Report</button>
    </div>
  </div>

  <script>
document.addEventListener("DOMContentLoaded", () => {
    // --- MAP & NEST FUNCTIONALITY ---
    const popup = document.getElementById("dataForm");
    const summaryPopup = document.getElementById("summaryPopup");
    const summaryContent = document.getElementById("summaryContent");
    let activePin = null;
    const pinData = new Map();
    let logs = [];

    const map = L.map("beachMap", { minZoom: 13, maxZoom: 19 })
        .setView([13.518718, 124.205692], 15);
    setTimeout(() => map.invalidateSize(), 300);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap"
    }).addTo(map);

    function createPinIcon() {
        return L.divIcon({
            className: "",
            html: '<div class="pin-div"></div>',
            iconSize: [24, 24],
            iconAnchor: [12, 24]
        });
    }

    function updateCoordFields(marker) {
        const latlng = marker.getLatLng();
        document.getElementById("coord_lat").value = latlng.lat;
        document.getElementById("coord_lng").value = latlng.lng;
        document.getElementById("coord_x").value = latlng.lat.toFixed(6);
        document.getElementById("coord_y").value = latlng.lng.toFixed(6);
    }

    function handlePinClick(marker, data = null) {
        activePin = marker;
        if (data) showSummary(marker, data);
        else {
            popup.classList.remove("hidden");
            document.getElementById("nestForm").reset();
            logs = [];
            renderLogs();
        }
        updateCoordFields(marker);
    }

    function createPin(lat, lng, data = null) {
        const marker = L.marker([lat, lng], {
            icon: createPinIcon(),
            riseOnHover: true,
            draggable: true
        }).addTo(map);
        if (data) pinData.set(marker, data);
        marker.on("click", () => handlePinClick(marker, data));
        marker.on("dragend", () => updateCoordFields(marker));
        return marker;
    }

    fetch("/nests")
        .then(r => r.json())
        .then(data => {
            if (!Array.isArray(data)) return;
            data.forEach(d => {
                if (d.lat && d.lng) createPin(Number(d.lat), Number(d.lng), d);
            });
        });

    map.on("click", e => {
        const marker = createPin(e.latlng.lat, e.latlng.lng);
        handlePinClick(marker);
    });

    // SMS Feedback
    const smsFeedback = document.createElement("p");
    smsFeedback.id = "smsFeedback";
    smsFeedback.style.fontWeight = "bold";
    smsFeedback.style.marginTop = "10px";
    popup.appendChild(smsFeedback);

    function showSmsFeedback(msg, isError = false) {
        smsFeedback.textContent = msg;
        smsFeedback.style.color = isError ? "red" : "green";
        smsFeedback.style.opacity = 1;
        setTimeout(() => {
            smsFeedback.style.transition = "opacity 1s";
            smsFeedback.style.opacity = 0;
        }, 5000);
    }

    document.getElementById("saveData").addEventListener("click", async () => {
        if (!activePin) return alert("No pin selected");

        const formEl = document.getElementById("nestForm");
        const formData = new FormData(formEl);
        updateCoordFields(activePin);
        formData.set("lat", document.getElementById("coord_lat").value);
        formData.set("lng", document.getElementById("coord_lng").value);
        formData.append("checked_logs", JSON.stringify(logs));

        const data = pinData.get(activePin);
        if (data?.id) formData.set("id", data.id);

        try {
            const res = await fetch("/nests", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value },
                body: formData
            });
            if (!res.ok) throw new Error("Network response not OK");
            const saved = await res.json();
            pinData.set(activePin, saved);
            popup.classList.add("hidden");
            showSummary(activePin, saved);
            showSmsFeedback("Nest saved! SMS alert sent successfully.");
        } catch (err) {
            console.error("Save error:", err);
            showSmsFeedback("Save failed or SMS alert not sent.", true);
        }
    });

    function renderLogs() {
        const container = document.getElementById("incubationLogs");
        container.innerHTML = "";
        logs.forEach(log => {
            container.innerHTML += `<div class="log-entry glass">
                <p><strong>Date:</strong> ${log.date}</p>
                <p><strong>Notes:</strong> ${log.notes}</p>
            </div>`;
        });
        container.innerHTML += `<div class="log-new">
            <label>Date Checked:</label>
            <input type="date" id="newDate">
            <label>Description:</label>
            <textarea id="newNotes"></textarea>
            <button type="button" class="btn primary" id="saveLog">Save Entry</button>
        </div>`;
        document.getElementById("saveLog").onclick = () => {
            const date = document.getElementById("newDate").value;
            const notes = document.getElementById("newNotes").value;
            if (!date || !notes) return alert("Fill both fields");
            logs.push({ date, notes });
            renderLogs();
        };
    }

    function showSummary(marker, data) {
        summaryContent.innerHTML = `
            <h3>Summary</h3>
            <p><strong>Nest Name:</strong> ${data.nest_name}</p>
            <p><strong>Date:</strong> ${data.nest_date || ""}</p>
            <p><strong>Species:</strong> ${data.species || ""}</p>
            <p><strong>Egg Count:</strong> ${data.egg_count || ""}</p>
            <p><strong>Hatched:</strong> ${data.egg_hatched || 0}</p>
            <p><strong>Not Hatched:</strong> ${data.egg_unhatched || 0}</p>
            <p><strong>Description:</strong> ${data.hatch_notes || ""}</p>
            <p><strong>Threats:</strong> ${(data.threat_type || "").toString()}</p>
            <hr>
            <p><strong>Latitude:</strong> ${data.lat}</p>
            <p><strong>Longitude:</strong> ${data.lng}</p>`;
        summaryPopup.classList.remove("hidden");
    }

    document.getElementById("deletePin").addEventListener("click", async () => {
        const data = pinData.get(activePin);
        if (data?.id) {
            await fetch(`/nests/${data.id}`, {
                method: "DELETE",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            });
        }
        map.removeLayer(activePin);
        pinData.delete(activePin);
        summaryPopup.classList.add("hidden");
    });

    document.getElementById("exportPinPdf").addEventListener("click", () => {
        const data = pinData.get(activePin);
        if (!data?.id) return alert("No record found.");
        window.open(`/generate-report/${data.id}`, "_blank");
    });

    document.getElementById("newNestReport").addEventListener("click", () => {
        summaryPopup.classList.add("hidden");
        logs = [];
        renderLogs();
        document.getElementById("nestForm").reset();
        popup.classList.remove("hidden");
    });

    document.getElementById("backFromForm").onclick = () => popup.classList.add("hidden");
    document.getElementById("backFromSummary").onclick = () => summaryPopup.classList.add("hidden");

    // --- CHARTS ---
const yearFilter = document.getElementById("yearFilter");
const monthFilter = document.getElementById("monthFilter");

let threatChart, eggChart, hatchBarChart, nestBarChart, pieChart;

// Load charts on page load
loadCharts();

// Reload charts when filters change
yearFilter.addEventListener("change", loadCharts);
monthFilter.addEventListener("change", loadCharts);

// Fetch and build charts
async function loadCharts() {
    const year = yearFilter.value || "";
    const month = monthFilter.value || "";

    const res = await fetch(`/charts/data?year=${year}&month=${month}`);
    const data = await res.json();

    buildThreatChart(data.threat);
    buildEggChart(data.eggs);
    buildHatchChart(data.hatch);
    buildNestChart(data.nest);
    buildPieChart(data.pie);
}


// Chart building functions remain the same as your current JS


function buildThreatChart(src) {
    if (threatChart) threatChart.destroy();
    threatChart = new Chart(document.getElementById("threatChart"), {
        type: "bar",
        data: {
            labels: src.labels,
            datasets: [{
                label: "Threat Reports",
                data: src.values,
                backgroundColor: "rgba(255, 99, 132, 0.6)"
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: "#ffffff", font: { size: 14 } } },
                tooltip: { titleColor: "#ffffff", bodyColor: "#ffffff", titleFont: { size: 14 }, bodyFont: { size: 13 } }
            },
            scales: {
                x: { ticks: { color: "#ffffff", font: { size: 13 } }, grid: { color: "rgba(255,255,255,0.25)" } },
                y: { ticks: { color: "#ffffff", font: { size: 13 } }, grid: { color: "rgba(255,255,255,0.25)" } }
            }
        }
    });
}

function buildEggChart(src) {
    if (eggChart) eggChart.destroy();
    eggChart = new Chart(document.getElementById("eggChart"), {
        type: "bar",
        data: {
            labels: src.labels,
            datasets: [{
                label: "Egg Count",
                data: src.values,
                backgroundColor: "rgba(54, 162, 235, 0.6)"
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: "#ffffff", font: { size: 14 } } },
                tooltip: { titleColor: "#ffffff", bodyColor: "#ffffff", titleFont: { size: 14 }, bodyFont: { size: 13 } }
            },
            scales: {
                x: { ticks: { color: "#ffffff", font: { size: 13 } }, grid: { color: "rgba(255,255,255,0.25)" } },
                y: { ticks: { color: "#ffffff", font: { size: 13 } }, grid: { color: "rgba(255,255,255,0.25)" } }
            }
        }
    });
}

function buildHatchChart(src) {
    if (hatchBarChart) hatchBarChart.destroy();
    hatchBarChart = new Chart(document.getElementById("hatchBarChart"), {
        type: "bar",
        data: {
            labels: src.labels,
            datasets: [
                { label: "Hatched", data: src.hatched, backgroundColor: "rgba(75, 192, 192, 0.6)" },
                { label: "Unhatched", data: src.unhatched, backgroundColor: "rgba(255, 206, 86, 0.6)" }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: "#ffffff", font: { size: 14 } } },
                tooltip: { titleColor: "#ffffff", bodyColor: "#ffffff", titleFont: { size: 14 }, bodyFont: { size: 13 } }
            },
            scales: {
                x: { ticks: { color: "#ffffff", font: { size: 13 } }, grid: { color: "rgba(255,255,255,0.25)" } },
                y: { ticks: { color: "#ffffff", font: { size: 13 } }, grid: { color: "rgba(255,255,255,0.25)" } }
            }
        }
    });
}

function buildNestChart(src) {
    if (nestBarChart) nestBarChart.destroy();
    nestBarChart = new Chart(document.getElementById("nestBarChart"), {
        type: "bar",
        data: {
            labels: src.labels,
            datasets: [{ label: "Nest Count", data: src.values, backgroundColor: "rgba(153, 102, 255, 0.6)" }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: "#ffffff", font: { size: 14 } } },
                tooltip: { titleColor: "#ffffff", bodyColor: "#ffffff", titleFont: { size: 14 }, bodyFont: { size: 13 } }
            },
            scales: {
                x: { ticks: { color: "#ffffff", font: { size: 13 } }, grid: { color: "rgba(255,255,255,0.25)" } },
                y: { ticks: { color: "#ffffff", font: { size: 13 } }, grid: { color: "rgba(255,255,255,0.25)" } }
            }
        }
    });
}

function buildPieChart(src) {
    if (pieChart) pieChart.destroy();
    pieChart = new Chart(document.getElementById("pieChart"), {
        type: "pie",
        data: {
            labels: ["Hatched", "Unhatched"],
            datasets: [{
                data: [src.hatched, src.unhatched],
                backgroundColor: ["rgba(75, 192, 192, 0.6)", "rgba(255, 99, 132, 0.6)"]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: "#ffffff", font: { size: 14 } } },
                tooltip: { titleColor: "#ffffff", bodyColor: "#ffffff", titleFont: { size: 14 }, bodyFont: { size: 13 } }
            }
        }
    });
}


    loadCharts(); // initial load
});

async function loadYearOptions() {
    const res = await fetch('/charts/years');
    const years = await res.json();

    const yearSelect = document.getElementById('yearFilter');
    years.forEach(y => {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y;
        yearSelect.appendChild(opt);
    });
}

async function loadCharts() {
    const year = document.getElementById('yearFilter').value;
    const month = document.getElementById('monthFilter').value;

    const res = await fetch(`/charts/data?year=${year}&month=${month}`);
    const data = await res.json();

    buildThreatChart(data.threat);
    buildEggChart(data.eggs);
    buildHatchChart(data.hatch);
    buildNestChart(data.nest);
    buildPieChart(data.pie);
}

document.getElementById('yearFilter').addEventListener('change', loadCharts);
document.getElementById('monthFilter').addEventListener('change', loadCharts);

loadYearOptions();
loadCharts();


</script>








</body>
</html>
