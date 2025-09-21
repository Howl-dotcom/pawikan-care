<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>PawikanCare</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Chelsea+Market&display=swap" rel="stylesheet">
</head>
<body>
  <div class="bg-wrap" aria-hidden="true">
    <img src="/turtle-bg.png" class="bg-image" alt="">
    <div class="bg-dim"></div>
  </div>

  <!-- LOGIN -->
  <main id="login" class="screen active" role="application" aria-labelledby="app-title">
    <h1 id="app-title" class="logo">PAWIKANCARE</h1>

     @if ($errors->any())
        <div style="color:red;">
            {{ $errors->first('email') }}
        </div>
    @endif

    <form id="loginForm" method="POST" action="{{ route('login.post') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">LOG IN</button>
</form>


    <div class="credit">Created by: Howl</div>
  </main>

  <!-- DASHBOARD -->
  <aside id="app" class="screen" aria-hidden="true">
    <nav class="sidebar" aria-label="Main menu">
      <button id="toggleSidebar" class="hamburger" aria-expanded="true" aria-controls="menu">&#9776;</button>
      <ul id="menu" class="menu">
        <li><button data-screen="dashboard" class="menu-btn">Dashboard</button></li>
        <li><button data-screen="log-nest" class="menu-btn">Nest Logging</button></li>
        <li><button data-screen="report-threat" class="menu-btn">Reports</button></li>
        <li><button data-screen="monitor-incubation" class="menu-btn">Incubation Monitor</button></li>
        <li><button data-screen="hatching-release" class="menu-btn">Hatching Release</button></li>
        <li><button data-screen="generate-reports" class="menu-btn">Generate Reports</button></li>
      </ul>
    </nav>

    <header class="topbar">
  @auth
    <h1>Welcome, {{ auth()->user()->name }}</h1>
  @endauth

  <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">Logout</button>
  </form>
</header>

    <section id="dashboard" class="page panel">
  <h2 class="section-title">View Quick Stats:</h2>
  <div class="stats-row">
    <div class="stats-card">
      <p><strong>Active Nests:</strong> (calculate later)</p>
    </div>

    <div class="stats-card">
      <p><strong>Hatching<br>Success Rates:</strong></p>
    </div>
  </div>

  <h2 class="section-title">Notification:</h2>
  <div class="notify-area panel-note"></div>
</section>

    <!-- Log New Nest -->
    <section id="log-nest" class="page panel hidden">
      <header class="page-header">
        <button class="back" data-screen="dashboard" aria-label="Back">&#8592;</button>
        <h2>Log New Nest</h2>
      </header>

      <form method="POST" action="{{ route('nests.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="field">
        <label for="date-nest">Date</label>
        <input id="date-nest" name="date" type="date" required>
    </div>

    <div class="field">
        <label for="species-nest">Species</label>
        <input id="species-nest" name="species" placeholder="Select species" required>
    </div>

    <div class="field full">
        <label for="photo-upload">Photo</label>
        <input id="photo-upload" type="file" name="photo" accept="image/*">
    </div>

    <div class="field full">
        <label for="notes-nest">Notes</label>
        <textarea id="notes-nest" name="notes" rows="5" placeholder="Add notes"></textarea>
    </div>

    <div class="actions full">
        <button class="btn primary" type="submit">Save</button>
    </div>
</form>

    </section>

    <!-- Monitor Incubation -->
    <section id="monitor-incubation" class="page panel hidden">
      <header class="page-header">
        <button class="back" data-screen="dashboard" aria-label="Back">&#8592;</button>
        <h2>Monitor Incubation</h2>
      </header>

      <div class="split">
        <div class="list">
          <button class="list-item active">Nest#1</button>
          <button class="list-item">Nest#2</button>
          <button class="list-item">Nest#3</button>
        </div>

        <form class="content" id="form-incubation">
          <div class="field">
            <label for="status-incubation">Status</label>
            <input id="status-incubation" name="status" list="status-options" placeholder="Select status">
            <datalist id="status-options">
              <option value="Good">
              <option value="Bad">
            </datalist>
          </div>

          <div class="field full">
            <label for="incubation-notes">Notes</label>
            <textarea id="incubation-notes" rows="6">Observation notes or changes</textarea>
          </div>

          <div class="info">System: Auto-calculates incubation days</div>
        </form>
      </div>
    </section>

    <!-- Hatching Release -->
    <section id="hatching-release" class="page panel hidden">
      <header class="page-header">
        <button class="back" data-screen="dashboard" aria-label="Back">&#8592;</button>
        <h2>Record Hatching Release</h2>
      </header>

      <div class="split">
        <div class="list">
          <button class="list-item active">Nest#1</button>
          <button class="list-item">Nest#2</button>
          <button class="list-item">Nest#3</button>
        </div>

        <form class="content" id="form-hatch">
          <div class="field">
            <label for="date-hatching">Date</label>
            <input id="date-hatching" name="hatchdate" type="date">
          </div>

          <div class="field">
            <label for="number-hatching">Number of Hatching</label>
            <input id="number-hatching" name="number" inputmode="numeric" pattern="[0-9]*">
          </div>

          <div class="field full">
            <label for="survival-rate">Estimated Survival Rate</label>
            <textarea id="survival-rate" rows="4" placeholder="Estimated Survival Rate"></textarea>
          </div>

          <div class="actions full">
            <button class="btn primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </section>

    <!-- Report Threat -->
    <section id="report-threat" class="page panel hidden">
      <header class="page-header">
        <button class="back" data-screen="dashboard" aria-label="Back">&#8592;</button>
        <h2>Report Environment Threat</h2>
        <p class="muted">Create Report</p>
      </header>

      <form id="form-report" class="form-grid" novalidate>
        <div class="field">
          <label for="nest-number">Nest #</label>
          <input id="nest-number" placeholder="Nest #">
        </div>

        <div class="field">
          <label for="threat-type">Threat Type</label>
          <input id="threat-type" placeholder="Debris, Light, Pollution, Unauthorized activity">
        </div>

        <div class="field">
          <label for="threat-photo">Upload Photo</label>
          <input id="threat-photo" type="file" accept="image/*">
        </div>

        <div class="field full">
          <label for="threat-notes">Notes</label>
          <textarea id="threat-notes" rows="5">Observation notes or changes</textarea>
        </div>

        <div class="actions full">
          <button class="btn primary" type="submit">Save</button>
        </div>
      </form>
    </section>

    <!-- Generate Reports -->
    <section id="generate-reports" class="page panel hidden">
      <header class="page-header">
        <button class="back" data-screen="dashboard" aria-label="Back">&#8592;</button>
        <h2>Generate Reports</h2>
      </header>

      <form class="panel" id="form-generate">
        <label for="generate-select">Select</label>
        <textarea id="generate-select" rows="6" placeholder="Select: Nest#, (Summary / Incident)"></textarea>
        <div class="actions full">
          <button class="btn primary" type="submit">Export Format: PDF</button>
        </div>
      </form>
    </section>
  </aside>

  <script>
    // Simple navigation script
    (function(){
      const login = document.getElementById('login');
      const app = document.getElementById('app');
      const screens = document.querySelectorAll('.page');
      const menuButtons = document.querySelectorAll('.menu-btn');
      const backButtons = document.querySelectorAll('.back');

      // handle login: show app screens
      
      // menu navigation
      menuButtons.forEach(btn=>{
        btn.addEventListener('click', ()=> showScreen(btn.dataset.screen));
      });

      backButtons.forEach(b=>{
        b.addEventListener('click', ()=> showScreen('dashboard'));
      });

      // small helper
      function showScreen(id){
        screens.forEach(s=>{
          if (s.id === id) {
            s.classList.remove('hidden');
            s.classList.add('active');
          } else {
            s.classList.add('hidden');
            s.classList.remove('active');
          }
        });
      }

      // sidebar toggle for small screens
      const toggle = document.getElementById('toggleSidebar');
      const menu = document.getElementById('menu');
      toggle.addEventListener('click', ()=>{
        const expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!expanded));
        menu.classList.toggle('open');
      });

      // logout
      document.getElementById('logout').addEventListener('click', ()=>{
        app.style.display = 'none';
        login.classList.add('active');
        login.setAttribute('aria-hidden', 'false');
        app.setAttribute('aria-hidden', 'true');
      });
    })();
  </script>
</body>
</html>
