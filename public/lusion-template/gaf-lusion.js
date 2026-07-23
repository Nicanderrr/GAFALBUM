const canvas = document.querySelector("#stage");
const ctx = canvas ? canvas.getContext("2d", { alpha: true }) : null;
const menuButton = document.querySelector(".menu-button");
const menuPanel = document.querySelector(".menu-panel");
const revealTargets = document.querySelectorAll(".section-kicker, .project-card, .goal-copy, .tunnel-text, .end p, .end a, .footer-grid > *");

let width = 0;
let height = 0;
let dpr = 1;
let pointerX = 0;
let pointerY = 0;
let targetX = 0;
let targetY = 0;
let time = 0;
const ribbons = [];

function resize() {
  if (!canvas || !ctx) return;
  dpr = Math.min(window.devicePixelRatio || 1, 2);
  width = window.innerWidth;
  height = window.innerHeight;
  canvas.width = Math.floor(width * dpr);
  canvas.height = Math.floor(height * dpr);
  canvas.style.width = `${width}px`;
  canvas.style.height = `${height}px`;
  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  buildRibbons();
}

function buildRibbons() {
  ribbons.length = 0;
  const count = width < 700 ? 7 : 12;
  for (let i = 0; i < count; i += 1) {
    ribbons.push({
      phase: Math.random() * Math.PI * 2,
      radius: Math.min(width, height) * (0.16 + Math.random() * 0.17),
      turns: 2 + Math.random() * 2,
      amp: 38 + Math.random() * 70,
      hue: i % 3,
      speed: 0.55 + Math.random() * 0.85
    });
  }
}

function drawHero(scrollRatio) {
  if (!ctx) return;
  const centerX = width * 0.53 + pointerX * 24;
  const centerY = height * 0.43 + pointerY * 18 - scrollRatio * height * 0.24;
  ctx.save();
  ctx.globalCompositeOperation = "source-over";
  ctx.fillStyle = `rgba(247, 248, 255, ${1 - scrollRatio * 0.9})`;
  ctx.fillRect(0, 0, width, height);

  const shadow = ctx.createRadialGradient(centerX, centerY, 10, centerX, centerY, Math.min(width, height) * 0.48);
  shadow.addColorStop(0, "rgba(0, 22, 236, .12)");
  shadow.addColorStop(0.5, "rgba(193, 255, 0, .09)");
  shadow.addColorStop(1, "rgba(255, 255, 255, 0)");
  ctx.fillStyle = shadow;
  ctx.fillRect(0, 0, width, height);

  ribbons.forEach((ribbon, index) => {
    const points = 190;
    ctx.beginPath();
    for (let i = 0; i < points; i += 1) {
      const p = i / (points - 1);
      const angle = p * Math.PI * 2 * ribbon.turns + ribbon.phase + time * ribbon.speed;
      const wave = Math.sin(angle * 1.7 + time * 1.2) * ribbon.amp;
      const squeeze = Math.sin(angle + index) * 0.35 + 0.72;
      const x = centerX + Math.cos(angle) * (ribbon.radius * squeeze + wave);
      const y = centerY + Math.sin(angle * 0.78) * (ribbon.radius * 0.56 + wave * 0.34);
      if (i === 0) ctx.moveTo(x, y);
      else ctx.lineTo(x, y);
    }
    const alpha = Math.max(0, 1 - scrollRatio * 1.4);
    ctx.lineWidth = 1.2 + index % 4;
    ctx.strokeStyle = ribbon.hue === 0
      ? `rgba(0, 0, 0, ${0.16 * alpha})`
      : ribbon.hue === 1
        ? `rgba(0, 22, 236, ${0.18 * alpha})`
        : `rgba(193, 255, 0, ${0.22 * alpha})`;
    ctx.stroke();
  });
  ctx.restore();
}

function drawDarkWorld(scrollY) {
  if (!ctx) return;
  const reel = document.querySelector(".reel");
  const footer = document.querySelector(".footer");
  if (!reel || !footer) return;
  const reelStart = reel.offsetTop;
  const end = footer.offsetTop;
  const active = scrollY > reelStart - height * 0.55 && scrollY < end;
  if (!active) return;

  const progress = Math.max(0, Math.min(1, (scrollY - reelStart + height * 0.5) / (end - reelStart)));
  ctx.save();
  ctx.globalCompositeOperation = "screen";
  const cx = width * (0.64 - progress * 0.18) + pointerX * 32;
  const cy = height * (0.46 + Math.sin(progress * Math.PI * 2) * 0.12) + pointerY * 32;

  for (let ring = 0; ring < 7; ring += 1) {
    ctx.beginPath();
    const segments = 120;
    const base = Math.min(width, height) * (0.12 + ring * 0.04 + progress * 0.06);
    for (let i = 0; i <= segments; i += 1) {
      const p = i / segments;
      const angle = p * Math.PI * 2;
      const noise = Math.sin(angle * (3 + ring) + time * (1.2 + ring * 0.08)) * 28;
      const x = cx + Math.cos(angle) * (base + noise);
      const y = cy + Math.sin(angle) * (base * 0.58 + noise * 0.5);
      if (i === 0) ctx.moveTo(x, y);
      else ctx.lineTo(x, y);
    }
    ctx.lineWidth = 1.4;
    ctx.strokeStyle = ring % 2 === 0 ? "rgba(193, 255, 0, .16)" : "rgba(118, 139, 255, .13)";
    ctx.stroke();
  }

  const beam = ctx.createRadialGradient(cx, cy, 0, cx, cy, Math.min(width, height) * 0.62);
  beam.addColorStop(0, "rgba(0, 22, 236, .12)");
  beam.addColorStop(0.45, "rgba(193, 255, 0, .08)");
  beam.addColorStop(1, "rgba(0, 0, 0, 0)");
  ctx.fillStyle = beam;
  ctx.fillRect(0, 0, width, height);
  ctx.restore();
}

function animate() {
  if (!ctx) return;
  time += 0.008;
  pointerX += (targetX - pointerX) * 0.08;
  pointerY += (targetY - pointerY) * 0.08;
  const scrollY = window.scrollY;
  const heroRatio = Math.min(1, scrollY / Math.max(1, height));

  ctx.clearRect(0, 0, width, height);
  drawHero(heroRatio);
  drawDarkWorld(scrollY);
  requestAnimationFrame(animate);
}

if (menuButton && menuPanel) {
  menuButton.addEventListener("click", () => {
    const isOpen = menuPanel.classList.toggle("is-open");
    menuButton.classList.toggle("is-open", isOpen);
    menuButton.setAttribute("aria-expanded", String(isOpen));
    menuPanel.setAttribute("aria-hidden", String(!isOpen));
  });
}

document.addEventListener("pointermove", (event) => {
  targetX = (event.clientX / window.innerWidth - 0.5) * 2;
  targetY = (event.clientY / window.innerHeight - 0.5) * 2;
});

revealTargets.forEach((element) => element.classList.add("reveal"));

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) entry.target.classList.add("in-view");
  });
}, { threshold: 0.16 });

revealTargets.forEach((element) => observer.observe(element));

window.addEventListener("resize", resize);
resize();
animate();
