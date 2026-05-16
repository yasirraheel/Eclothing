// Dashboard interactions and lightweight animations.

document.addEventListener('DOMContentLoaded', () => {
  const loadingScreen = document.getElementById('loadingScreen');
  const menuToggle = document.getElementById('menuToggle');
  const sidebar = document.getElementById('sidebar');
  const searchInput = document.getElementById('searchInput');
  const productCards = Array.from(document.querySelectorAll('.product-card'));
  const revealItems = Array.from(document.querySelectorAll('.reveal'));
  const progressRing = document.querySelector('.progress-ring');

  const weeklySales = [68, 82, 74, 104, 92, 118, 136];
  const monthlyRevenue = [180, 240, 210, 310, 280, 360];
  const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

  // Hide the loader after the page settles.
  window.setTimeout(() => {
    loadingScreen.classList.add('hide');
  }, 850);

  // Mobile sidebar toggle.
  menuToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
  });

  // Small reveal animation for cards and panels.
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  revealItems.forEach((item) => observer.observe(item));

  // Animate the progress ring using the data-percent value.
  if (progressRing) {
    const target = Number(progressRing.dataset.percent || 0);
    let current = 0;
    const interval = window.setInterval(() => {
      current += 1;
      progressRing.style.background = `conic-gradient(var(--orange) ${current * 3.6}deg, rgba(245, 114, 36, 0.12) 0deg)`;
      if (current >= target) {
        window.clearInterval(interval);
      }
    }, 18);
  }

  // Build the weekly sales SVG chart.
  renderWeeklySalesChart(weeklySales);

  // Build the monthly revenue bar chart.
  renderRevenueBars(monthLabels, monthlyRevenue);

  // Filter product cards using the search bar.
  searchInput?.addEventListener('input', (event) => {
    const value = event.target.value.trim().toLowerCase();

    productCards.forEach((card) => {
      const matches = card.dataset.name.toLowerCase().includes(value);
      card.style.display = matches ? 'flex' : 'none';
    });
  });

  // Button click micro-interaction.
  document.querySelectorAll('.buy-button').forEach((button) => {
    button.addEventListener('click', (event) => {
      if (button.disabled) return;
      const originalText = button.textContent;
      button.textContent = 'Added';
      button.style.background = 'linear-gradient(135deg, #18a058, #2ecf76)';
      window.setTimeout(() => {
        button.textContent = originalText;
        button.style.background = '';
      }, 900);
    });
  });

  // Close sidebar when the user taps outside on mobile.
  document.addEventListener('click', (event) => {
    const clickedInsideSidebar = sidebar.contains(event.target);
    const clickedToggle = menuToggle.contains(event.target);

    if (window.innerWidth <= 980 && !clickedInsideSidebar && !clickedToggle) {
      sidebar.classList.remove('open');
    }
  });
});

function renderWeeklySalesChart(values) {
  const line = document.getElementById('salesLine');
  const area = document.getElementById('salesArea');
  const pointsGroup = document.getElementById('salesPoints');
  const gridLines = document.querySelector('.grid-lines');

  const width = 640;
  const height = 280;
  const padding = 34;
  const chartHeight = height - padding * 2;
  const chartWidth = width - padding * 2;
  const maxValue = Math.max(...values) * 1.15;

  // Draw background grid lines.
  gridLines.innerHTML = '';
  for (let index = 0; index < 4; index += 1) {
    const y = padding + (chartHeight / 3) * index;
    gridLines.insertAdjacentHTML('beforeend', `<line x1="${padding}" y1="${y}" x2="${width - padding}" y2="${y}"></line>`);
  }

  const points = values.map((value, index) => {
    const x = padding + (chartWidth / (values.length - 1)) * index;
    const y = padding + (chartHeight - (value / maxValue) * chartHeight);
    return { x, y };
  });

  const linePath = points.map((point, index) => `${index === 0 ? 'M' : 'L'} ${point.x} ${point.y}`).join(' ');
  const areaPath = `${linePath} L ${points[points.length - 1].x} ${height - padding} L ${points[0].x} ${height - padding} Z`;

  line.setAttribute('d', linePath);
  area.setAttribute('d', areaPath);

  pointsGroup.innerHTML = points.map((point) => `
    <circle class="chart-point" cx="${point.x}" cy="${point.y}" r="6"></circle>
  `).join('');
}

function renderRevenueBars(labels, values) {
  const container = document.getElementById('revenueBars');
  const maxValue = Math.max(...values) * 1.1;

  container.innerHTML = values.map((value, index) => {
    const heightPercent = Math.max((value / maxValue) * 100, 14);
    return `
      <div class="bar-wrap">
        <div class="bar-track">
          <div class="bar-fill" style="height:${heightPercent}%;"></div>
        </div>
        <div class="bar-value">Rs. ${value}k</div>
        <div class="bar-label">${labels[index]}</div>
      </div>
    `;
  }).join('');

  // Stagger the bar animation for a smoother entrance.
  const bars = container.querySelectorAll('.bar-fill');
  bars.forEach((bar, index) => {
    window.setTimeout(() => {
      bar.style.transform = 'scaleY(1)';
    }, 180 + index * 110);
  });
}
