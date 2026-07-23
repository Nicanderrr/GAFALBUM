(function () {
  const removeStartupLoader = () => {
    const loader = document.querySelector("#preloader");
    if (!loader) return;
    loader.style.setProperty("display", "none", "important");
    loader.style.setProperty("opacity", "0", "important");
    loader.style.setProperty("visibility", "hidden", "important");
    loader.style.setProperty("pointer-events", "none", "important");
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", removeStartupLoader, { once: true });
  } else {
    removeStartupLoader();
  }

  const refreshAnimationRuntime = () => {
    window.dispatchEvent(new Event("resize"));
  };

  [120, 600, 1600, 3200].forEach((delay) => window.setTimeout(refreshAnimationRuntime, delay));

  const projectData = {
    oryzo_ai: {
      title: "Oryzo AI",
      services: "concept / web / design / development / 3d / animation",
      image: "/assets/projects/oryzo_ai/home.webp",
      colorHighlight: "#dc5000",
      colorBtnBg: "#ffedd7",
      colorBtnText: "#192743"
    },
    of_the_oak: {
      title: "Of The Oak",
      services: "web / design / development / 3d / animation",
      image: "/assets/projects/of_the_oak/home.webp"
    },
    devin_ai: {
      title: "Devin AI",
      services: "web / design / development / 3d",
      image: "/assets/projects/devin_ai/home.webp"
    },
    porsche_dream_machine: {
      title: "Porsche: Dream Machine",
      services: "concept / 3D illustration / mograph / video",
      image: "/assets/projects/porsche_dream_machine/home.webp"
    },
    synthetic_human: {
      title: "Synthetic Human",
      services: "web / design / development / 3d",
      image: "/assets/projects/synthetic_human/home.webp"
    },
    spatial_fusion: {
      title: "Meta: Spatial Fusion",
      services: "web / design / development / 3d",
      image: "/assets/projects/spatial_fusion/home.webp"
    },
    spaace: {
      title: "Spaace - NFT Marketplace",
      services: "web / design / development / 3d / web3",
      image: "/assets/projects/spaace/home.webp"
    },
    ddd_2024: {
      title: "DDD 2024",
      services: "web / design / development / 3d",
      image: "/assets/projects/ddd_2024/home.webp"
    },
    choo_choo_world: {
      title: "Choo Choo World",
      services: "concept / web / game design / 3d",
      image: "/assets/projects/choo_choo_world/home.webp"
    },
    soda_experience: {
      title: "Soda Experience",
      services: "AR / development / 3d",
      image: "/assets/projects/soda_experience/home.webp"
    }
  };

  const slugFromCard = (card) => {
    if (card.dataset.id) return card.dataset.id;
    const href = card.getAttribute("href") || "";
    const fromHref = href.match(/\/projects\/([^/?#]+)/);
    if (fromHref) return fromHref[1];
    const title = card.querySelector("strong, .project-item-line-2-inner")?.textContent || "";
    return title.trim().toLowerCase().replace(/[^a-z0-9]+/g, "_").replace(/^_|_$/g, "");
  };

  const dataFromCard = (card) => {
    const slug = slugFromCard(card);
    const local = projectData[slug] || {};
    return {
      slug,
      title: local.title || card.querySelector("strong, .project-item-line-2-inner")?.textContent?.trim() || "Project",
      services: local.services || card.querySelector("small, .project-item-line-1")?.textContent?.trim() || "",
      image: local.image || card.dataset.image || "",
      href: card.dataset.href || card.getAttribute("href") || `/projects/${slug}`,
      colorBg: card.dataset.colorBg || "#111111",
      colorText: card.dataset.colorText || "#f0f1fa",
      colorHighlight: local.colorHighlight || card.dataset.colorHighlight || card.dataset.colorText || "#f0f1fa",
      colorBtnBg: local.colorBtnBg || "#ffffff",
      colorBtnText: local.colorBtnText || "#000000"
    };
  };

  const mountHomeSlides = () => {
    const host = document.querySelector("#home-hero-visual-container");
    if (!host || host.dataset.homeslidesReady) return;

    const slides = ["oryzo_ai", "of_the_oak", "devin_ai", "porsche_dream_machine", "spatial_fusion"].map((slug) => ({
      slug,
      ...projectData[slug]
    }));

    host.dataset.homeslidesReady = "true";
    host.innerHTML = [
      '<section id="homeslides" aria-label="Featured project slideshow">',
      '  <div class="homeslides__media"></div>',
      '  <div class="homeslides__shade"></div>',
      '  <div class="homeslides__content">',
      '    <div class="homeslides__count"></div>',
      '    <h2 class="homeslides__title"></h2>',
      '    <p class="homeslides__services"></p>',
      '    <a class="homeslides__link" href="/projects/oryzo_ai"><span>View project</span></a>',
      '  </div>',
      '  <div class="homeslides__controls">',
      '    <button class="homeslides__button" type="button" data-home-slide-prev aria-label="Previous slide">Prev</button>',
      '    <button class="homeslides__button" type="button" data-home-slide-next aria-label="Next slide">Next</button>',
      '  </div>',
      '  <div class="homeslides__progress" aria-hidden="true"></div>',
      '</section>'
    ].join("");

    const root = host.querySelector("#homeslides");
    const media = root.querySelector(".homeslides__media");
    const count = root.querySelector(".homeslides__count");
    const title = root.querySelector(".homeslides__title");
    const services = root.querySelector(".homeslides__services");
    const link = root.querySelector(".homeslides__link");
    const progress = root.querySelector(".homeslides__progress");
    let index = 0;
    let timer = 0;

    const render = (direction = 1) => {
      const slide = slides[index];
      root.dataset.direction = String(direction);
      root.classList.remove("is-settled");
      media.style.backgroundImage = `url("${slide.image}")`;
      count.textContent = `${String(index + 1).padStart(2, "0")} / ${String(slides.length).padStart(2, "0")}`;
      title.textContent = slide.title;
      services.textContent = slide.services;
      link.href = `/projects/${slide.slug}`;
      progress.style.animation = "none";
      progress.offsetHeight;
      progress.style.animation = "";
      requestAnimationFrame(() => root.classList.add("is-settled"));
    };

    const go = (step) => {
      window.clearInterval(timer);
      index = (index + step + slides.length) % slides.length;
      render(step);
      timer = window.setInterval(() => go(1), 5200);
    };

    root.querySelector("[data-home-slide-prev]").addEventListener("click", () => go(-1));
    root.querySelector("[data-home-slide-next]").addEventListener("click", () => go(1));
    timer = window.setInterval(() => go(1), 5200);
    render();
  };

  const enableHomeSmoothScrollFallback = () => {
    if (document.body.classList.contains("static-project-detail-body")) return;
    if (document.documentElement.dataset.homeSmoothScrollReady) return;

    const movers = [
      document.querySelector("#page-container"),
      document.querySelector("#page-extra-sections"),
      document.querySelector("#scroll-nav-section")
    ].filter(Boolean);

    if (!movers.length) return;

    document.documentElement.dataset.homeSmoothScrollReady = "true";

    let current = 0;
    let target = 0;
    let max = 0;
    let raf = 0;

    const measure = () => {
      const bottoms = movers.map((el) => el.offsetTop + el.scrollHeight);
      max = Math.max(0, Math.max(...bottoms) - window.innerHeight);
      target = clamp(target, 0, max);
      current = clamp(current, 0, max);
    };

    const apply = () => {
      const value = `translate3d(0, ${(-current).toFixed(2)}px, 0)`;
      movers.forEach((el) => {
        el.style.transform = value;
        el.style.willChange = "transform";
      });
      const progress = max ? current / max : 0;
      const heroProgress = clamp(current / Math.max(window.innerHeight * 0.72, 1), 0, 1);
      const titleOpacity = clamp(1 - heroProgress * 1.35, 0, 1);
      document.documentElement.classList.toggle("is-white-bg", current > 12);
      document.documentElement.style.setProperty("--home-scroll-progress", progress.toFixed(4));
      document.documentElement.style.setProperty("--home-hero-scroll-progress", heroProgress.toFixed(4));
      document.documentElement.style.setProperty("--home-hero-title-opacity", titleOpacity.toFixed(4));
    };

    const tick = () => {
      current += (target - current) * 0.12;
      if (Math.abs(target - current) < 0.35) current = target;
      apply();
      if (current !== target) {
        raf = requestAnimationFrame(tick);
      } else {
        raf = 0;
      }
    };

    const start = () => {
      if (!raf) raf = requestAnimationFrame(tick);
    };

    const scrollBy = (delta) => {
      measure();
      target = clamp(target + delta, 0, max);
      start();
    };

    window.addEventListener("wheel", (event) => {
      if (document.documentElement.classList.contains("project-experience-open")) return;
      if (document.body.classList.contains("static-project-detail-body")) return;
      if (event.ctrlKey || event.metaKey) return;
      event.preventDefault();
      event.stopImmediatePropagation();
      scrollBy(wheelDelta(event) * 0.14);
    }, { capture: true, passive: false });

    window.addEventListener("keydown", (event) => {
      if (event.defaultPrevented) return;
      const amounts = {
        ArrowDown: 120,
        ArrowUp: -120,
        PageDown: window.innerHeight * 0.85,
        PageUp: -window.innerHeight * 0.85,
        Home: -Infinity,
        End: Infinity
      };
      if (!(event.key in amounts)) return;
      event.preventDefault();
      measure();
      const amount = amounts[event.key];
      target = amount === Infinity ? max : amount === -Infinity ? 0 : clamp(target + amount, 0, max);
      start();
    }, true);

    window.addEventListener("resize", () => {
      measure();
      apply();
    });

    measure();
    apply();
  };

  const ensureOverlay = () => {
    let overlay = document.querySelector("[data-project-fallback]");
    if (overlay) return overlay;

    overlay = document.createElement("section");
    overlay.className = "project-fallback";
    overlay.setAttribute("data-project-fallback", "");
    overlay.setAttribute("aria-hidden", "true");
    overlay.innerHTML = [
      '<div class="project-fallback__panel" role="dialog" aria-modal="true" aria-labelledby="project-fallback-title">',
      '  <button class="project-fallback__close" type="button" aria-label="Close project details">Close</button>',
      '  <div class="project-fallback__media"></div>',
      '  <div class="project-fallback__content">',
      '    <p class="project-fallback__services"></p>',
      '    <h2 id="project-fallback-title"></h2>',
      '    <p class="project-fallback__copy">Project preview is available in this static replica. The original template routes this card to a full project page.</p>',
      '  </div>',
      '</div>'
    ].join("");
    document.body.appendChild(overlay);

    overlay.addEventListener("click", (event) => {
      if (event.target === overlay || event.target.closest(".project-fallback__close")) {
        closeOverlay();
      }
    });
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && overlay.classList.contains("is-open")) closeOverlay();
    });

    return overlay;
  };

  const closeOverlay = () => {
    const overlay = document.querySelector("[data-project-fallback]");
    if (!overlay) return;
    overlay.classList.remove("is-open");
    overlay.setAttribute("aria-hidden", "true");
    document.documentElement.classList.remove("project-fallback-open");
  };

  const openOverlay = (data) => {
    const overlay = ensureOverlay();
    overlay.querySelector("#project-fallback-title").textContent = data.title;
    overlay.querySelector(".project-fallback__services").textContent = data.services;
    const media = overlay.querySelector(".project-fallback__media");
    media.style.backgroundImage = data.image ? `url("${data.image}")` : "";
    overlay.classList.add("is-open");
    overlay.setAttribute("aria-hidden", "false");
    document.documentElement.classList.add("project-fallback-open");
    history.pushState(history.state, "", `#${data.slug}`);
  };

  const ensureLocalReelOverlay = () => {
    let overlay = document.querySelector("[data-local-reel-overlay]");
    if (overlay) return overlay;

    overlay = document.createElement("section");
    overlay.className = "local-reel-overlay";
    overlay.setAttribute("data-local-reel-overlay", "");
    overlay.setAttribute("aria-hidden", "true");
    overlay.innerHTML = [
      '<button class="local-reel-overlay__close" type="button" aria-label="Close reel">Close</button>',
      '<video class="local-reel-overlay__video" src="/assets/textures/reel/desktop.mp4" controls playsinline preload="metadata"></video>'
    ].join("");
    document.body.appendChild(overlay);

    const close = () => {
      const video = overlay.querySelector("video");
      video.pause();
      overlay.classList.remove("is-open");
      overlay.setAttribute("aria-hidden", "true");
      document.documentElement.classList.remove("local-reel-open");
    };

    overlay.addEventListener("click", (event) => {
      if (event.target === overlay || event.target.closest(".local-reel-overlay__close")) close();
    });
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && overlay.classList.contains("is-open")) close();
    });

    return overlay;
  };

  const openLocalReel = () => {
    const overlay = ensureLocalReelOverlay();
    const video = overlay.querySelector("video");
    overlay.classList.add("is-open");
    overlay.setAttribute("aria-hidden", "false");
    document.documentElement.classList.add("local-reel-open");
    video.currentTime = 0;
    video.play().catch(() => {});
  };

  const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

  const closeProjectExperience = () => {
    const current = document.querySelector("[data-project-experience]");
    if (!current) return;
    current.classList.remove("is-open");
    document.documentElement.classList.remove(
      "project-experience-open",
      "is-project-details-active",
      "project-native-reveal-fix",
      "project-route-fallback-active"
    );
    document.documentElement.style.removeProperty("--project-details-bg");
    document.documentElement.style.removeProperty("--project-details-text");
    document.documentElement.style.removeProperty("--project-details-highlight");
    document.documentElement.style.removeProperty("--project-details-btn-bg");
    document.documentElement.style.removeProperty("--project-details-btn-text");
    document.documentElement.style.removeProperty("--project-details-image");
    document.body.classList.remove("static-project-detail-body");
    window.setTimeout(() => current.remove(), 700);
    if (location.pathname.replace(/^\/|\/$/g, "").startsWith("projects/")) {
      history.pushState(history.state, "", "/");
    } else if (location.pathname.includes("/gallery/")) {
      history.pushState(history.state, "", "/gallery");
    }
  };

  const applyProjectScrollEffects = (details, rail, progress, explicitVelocity) => {
    const previousProgress = Number(details.dataset.previousProgress || progress);
    const measuredVelocity = clamp((progress - previousProgress) * 28, -1, 1);
    const velocity = explicitVelocity ?? measuredVelocity;
    const meta = details.querySelector("#project-details-meta");
    const fadeProgress = clamp((progress - 0.07) / 0.25, 0, 1);
    const metaOpacity = Math.pow(1 - fadeProgress, 1.7);

    details.dataset.previousProgress = String(progress);
    details.style.setProperty("--project-scroll-progress", progress.toFixed(4));
    details.style.setProperty("--project-scroll-velocity", velocity.toFixed(4));
    if (meta) {
      meta.getAnimations?.().forEach((animation) => animation.cancel());
      meta.style.setProperty("opacity", metaOpacity.toFixed(4), "important");
      meta.style.setProperty(
        "transform",
        `translate3d(${progress * -1.15}em, calc(-50% - ${progress * 1.4}em), 0)`,
        "important"
      );
    }
    rail.querySelectorAll(".project-details-item").forEach((item, index) => {
      item.getAnimations?.().forEach((animation) => animation.cancel());
      const lift = Math.sin(progress * Math.PI * 1.4 + index * 0.72) * velocity * 20;
      item.style.setProperty("transform", `translate3d(0, ${lift.toFixed(2)}px, 0)`, "important");
    });
  };

  const syncProjectExperienceRail = (stage, explicitVelocity) => {
    const rail = stage.querySelector("#project-details-items-move-container");
    const details = stage.querySelector("#project-details");
    if (!rail || !details) return;

    rail.getAnimations?.().forEach((animation) => animation.cancel());
    rail.style.animation = "none";
    const maxScroll = Math.max(1, stage.scrollHeight - stage.clientHeight);
    const progress = Math.min(1, Math.max(0, stage.scrollTop / maxScroll));
    const travel = Math.max(0, rail.scrollWidth - stage.clientWidth * 0.92);
    const previousProgress = Number(details.dataset.previousProgress || progress);
    const measuredVelocity = clamp((progress - previousProgress) * 28, -1, 1);
    const storedVelocity = Number(details.dataset.projectVelocity || "0");
    const storedMomentum = Number(details.dataset.projectMomentum || "0");
    const velocity = explicitVelocity ?? (storedVelocity || measuredVelocity || storedMomentum * 0.86);
    details.dataset.projectMomentum = Math.abs(velocity) > 0.02 ? String(velocity * 0.82) : "0";
    rail.style.transform = `translate3d(${-travel * progress}px, 0, 0) skewX(${(-velocity * 1.2).toFixed(3)}deg)`;
    applyProjectScrollEffects(details, rail, progress, velocity);
    details.dataset.projectVelocity = "0";
  };

  const wheelDelta = (event) => {
    const multiplier = event.deltaMode === 1 ? 18 : event.deltaMode === 2 ? window.innerHeight : 1;
    return (event.deltaY + event.deltaX) * multiplier;
  };

  const syncStaticProjectRail = (explicitVelocity) => {
    if (document.querySelector("[data-project-experience]")) return;
    const details = document.querySelector(".static-project-detail-body #project-details");
    const rail = document.querySelector(".static-project-detail-body #project-details-items-move-container");
    if (!details || !rail) return;

    rail.getAnimations?.().forEach((animation) => animation.cancel());
    rail.style.animation = "none";
    const maxScroll = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
    const virtualScrollY = Number(details.dataset.virtualScrollY || window.scrollY || 0);
    const progress = Math.min(1, Math.max(0, virtualScrollY / maxScroll));
    const travel = Math.max(0, rail.scrollWidth - window.innerWidth * 0.92);
    const previousProgress = Number(details.dataset.previousProgress || progress);
    const measuredVelocity = clamp((progress - previousProgress) * 28, -1, 1);
    const storedVelocity = Number(details.dataset.projectVelocity || "0");
    const storedMomentum = Number(details.dataset.projectMomentum || "0");
    const velocity = explicitVelocity ?? (storedVelocity || measuredVelocity || storedMomentum * 0.86);
    details.dataset.projectMomentum = Math.abs(velocity) > 0.02 ? String(velocity * 0.82) : "0";
    rail.style.transform = `translate3d(${-travel * progress}px, 0, 0) skewX(${(-velocity * 1.2).toFixed(3)}deg)`;
    applyProjectScrollEffects(details, rail, progress, velocity);
    details.dataset.projectVelocity = "0";
  };

  const routeWheelToProjectExperience = (event) => {
    const stage = document.querySelector("[data-project-experience]");
    if (!stage || !document.documentElement.classList.contains("project-experience-open")) return;
    if (event.ctrlKey || event.metaKey) return;

    const delta = wheelDelta(event);
    const nextScrollTop = Math.min(
      stage.scrollHeight - stage.clientHeight,
      Math.max(0, stage.scrollTop + delta)
    );
    if (nextScrollTop === stage.scrollTop) return;
    event.preventDefault();
    const velocity = clamp(delta / 900, -1, 1);
    stage.querySelector("#project-details")?.setAttribute("data-project-velocity", String(velocity));
    stage.scrollTop = nextScrollTop;
    syncProjectExperienceRail(stage, velocity);
  };

  const routeWheelToStaticProject = (event) => {
    if (document.querySelector("[data-project-experience]")) return;
    if (!document.body.classList.contains("static-project-detail-body")) return;
    if (event.ctrlKey || event.metaKey) return;

    const details = document.querySelector(".static-project-detail-body #project-details");
    if (!details) return;

    const currentScrollY = Number(details.dataset.virtualScrollY || window.scrollY || 0);
    const maxScroll = Math.max(0, document.documentElement.scrollHeight - window.innerHeight);
    const delta = wheelDelta(event);
    const nextScrollY = Math.min(maxScroll, Math.max(0, currentScrollY + delta));
    if (nextScrollY === currentScrollY) return;
    event.preventDefault();
    const velocity = clamp(delta / 900, -1, 1);
    details.dataset.projectVelocity = String(velocity);
    details.dataset.virtualScrollY = String(nextScrollY);
    window.scrollTo(0, 0);
    syncStaticProjectRail(velocity);
  };

  const cancelImportedProjectAnimations = (root) => {
    root.querySelectorAll("*").forEach((element) => {
      element.getAnimations?.().forEach((animation) => animation.cancel());
    });
  };

  const layoutStaticProjectMedia = (root, image) => {
    root.querySelectorAll(".project-details-item").forEach((item) => {
      const width = Number(item.dataset.width) || 1280;
      const height = Number(item.dataset.height) || 720;
      const ratio = width / Math.max(1, height);
      item.style.width = `calc((100vh - var(--base-padding-y) * 4 - var(--header-size) * 3.9) * ${ratio})`;
      item.style.height = "100%";
      if (item.classList.contains("is-image") || item.classList.contains("is-video")) {
        item.style.backgroundImage = `url("${image}")`;
      }
    });
  };

  const enableProjectPointerEffects = (root) => {
    const details = root.querySelector("#project-details");
    if (!details || details.dataset.pointerEffectsReady) return;
    details.dataset.pointerEffectsReady = "true";

    const trails = [0, 1, 2].map((index) => {
      const trail = document.createElement("span");
      trail.className = "project-pointer-trail";
      trail.style.opacity = String(0.34 - index * 0.08);
      details.appendChild(trail);
      return {
        element: trail,
        x: window.innerWidth * 0.5,
        y: window.innerHeight * 0.5,
        speed: 0.2 - index * 0.045
      };
    });

    const pointer = {
      x: window.innerWidth * 0.5,
      y: window.innerHeight * 0.5,
      active: false
    };

    const updatePointer = (event) => {
      const rect = details.getBoundingClientRect();
      pointer.x = event.clientX - rect.left;
      pointer.y = event.clientY - rect.top;
      pointer.active = true;
      details.style.setProperty("--project-pointer-x", clamp(event.clientX / window.innerWidth, 0, 1).toFixed(4));
      details.style.setProperty("--project-pointer-y", clamp(event.clientY / window.innerHeight, 0, 1).toFixed(4));
    };

    const animate = () => {
      if (!details.isConnected) return;
      trails.forEach((trail, index) => {
        trail.x += (pointer.x - trail.x) * trail.speed;
        trail.y += (pointer.y - trail.y) * trail.speed;
        const scale = pointer.active ? 1 - index * 0.18 : 0.35;
        const opacity = pointer.active ? 0.34 - index * 0.08 : 0;
        trail.element.style.opacity = opacity.toFixed(3);
        trail.element.style.transform = `translate3d(${trail.x.toFixed(1)}px, ${trail.y.toFixed(1)}px, 0) translate3d(-50%, -50%, 0) scale(${scale.toFixed(3)})`;
      });
      requestAnimationFrame(animate);
    };

    details.addEventListener("pointermove", updatePointer, { passive: true });
    details.addEventListener("pointerleave", () => {
      pointer.active = false;
    }, { passive: true });
    requestAnimationFrame(animate);
  };

  const buildProjectExperience = async (data, card) => {
    const response = await fetch(`${data.href}/`, { credentials: "same-origin" });
    if (!response.ok) {
      window.location.href = data.href;
      return;
    }

    const html = await response.text();
    const routeDocument = new DOMParser().parseFromString(html, "text/html");
    const details = routeDocument.querySelector("#project-details");
    if (!details) {
      window.location.href = data.href;
      return;
    }

    document.querySelector("[data-project-experience]")?.remove();

    const stage = document.createElement("main");
    stage.className = "project-experience static-project-detail-body";
    stage.setAttribute("data-project-experience", "");
    stage.style.setProperty("--project-details-bg", data.colorBg);
    stage.style.setProperty("--project-details-text", data.colorText);
    stage.style.setProperty("--project-details-highlight", data.colorHighlight);
    stage.style.setProperty("--project-details-btn-bg", data.colorBtnBg);
    stage.style.setProperty("--project-details-btn-text", data.colorBtnText);
    stage.style.setProperty("--project-details-image", `url("${data.image}")`);
    document.documentElement.style.setProperty("--project-details-bg", data.colorBg);
    document.documentElement.style.setProperty("--project-details-text", data.colorText);
    document.documentElement.style.setProperty("--project-details-highlight", data.colorHighlight);
    document.documentElement.style.setProperty("--project-details-btn-bg", data.colorBtnBg);
    document.documentElement.style.setProperty("--project-details-btn-text", data.colorBtnText);
    document.documentElement.style.setProperty("--project-details-image", `url("${data.image}")`);
    stage.innerHTML = [
      '<div id="project" class="page"></div>'
    ].join("");
    stage.querySelector("#project").appendChild(document.importNode(details, true));
    cancelImportedProjectAnimations(stage);
    layoutStaticProjectMedia(stage, data.image);
    enableProjectPointerEffects(stage);
    document.body.appendChild(stage);

    const transition = document.createElement("div");
    transition.className = "project-card-transition";
    transition.style.backgroundImage = `url("${data.image}")`;
    transition.style.backgroundColor = data.colorBg;
    document.body.appendChild(transition);

    const sourceRect = card.getBoundingClientRect();
    transition.style.left = `${sourceRect.left}px`;
    transition.style.top = `${sourceRect.top}px`;
    transition.style.width = `${sourceRect.width}px`;
    transition.style.height = `${sourceRect.height}px`;
    transition.getBoundingClientRect();

    document.documentElement.classList.remove("project-native-reveal-fix", "project-route-fallback-active");
    document.documentElement.classList.add("project-experience-open", "is-project-details-active");
    document.body.classList.add("static-project-detail-body");
    document.title = routeDocument.title || `${data.title} - Trayse Studio`;
    history.pushState(history.state, "", data.href);

    requestAnimationFrame(() => {
      transition.classList.add("is-expanding");
      stage.classList.add("is-open");
      syncProjectExperienceRail(stage);
    });

    stage.addEventListener("scroll", () => syncProjectExperienceRail(stage), { passive: true });
    window.addEventListener("resize", () => syncProjectExperienceRail(stage));
    window.setTimeout(() => transition.remove(), 950);
  };

  document.addEventListener("click", (event) => {
    const link = event.target.closest('a[href="/about"], a[href="/projects"]');
    if (!link) return;
    event.preventDefault();
    event.stopImmediatePropagation();
    window.location.href = link.getAttribute("href");
  }, true);

  let templateReadyAt = 0;

  const getTemplateReadyAt = () => {
    if (!templateReadyAt) templateReadyAt = performance.now() + 1200;
    return templateReadyAt;
  };

  const isTemplateReady = () => {
    return true;
  };

  const syncTemplateReadyClass = () => {
    document.documentElement.classList.toggle("project-links-ready", isTemplateReady());
    if (!document.documentElement.classList.contains("project-links-ready")) {
      requestAnimationFrame(syncTemplateReadyClass);
    }
  };

  const hasMountedProjectDetails = (slug) => {
    return location.pathname.replace(/^\/|\/$/g, "") === `projects/${slug}` &&
      !!document.querySelector("#project-details #project-details-title");
  };

  const mountProjectRouteFallback = async (slug, href) => {
    if (hasMountedProjectDetails(slug)) return;

    const response = await fetch(href, { credentials: "same-origin" });
    if (!response.ok) return;

    const html = await response.text();
    const routeDocument = new DOMParser().parseFromString(html, "text/html");
    const projectPage = routeDocument.querySelector("#project.page");
    const pageContainer = document.querySelector("#page-container-inner");
    if (!projectPage || !pageContainer || hasMountedProjectDetails(slug)) return;

    document.title = routeDocument.title || document.title;
    pageContainer.replaceChildren(document.importNode(projectPage, true));
    document.body.classList.add("static-project-detail-body");
    document.documentElement.classList.add("is-project-details-active", "project-route-fallback-active");
    requestAnimationFrame(() => {
      document.querySelector("#project")?.classList.add("project-route-fallback-visible");
    });
  };

  const watchProjectRoute = (slug, href) => {
    const rescueStalledNativeRoute = () => {
      if (location.pathname.replace(/^\/|\/$/g, "") !== `projects/${slug}`) return;
      const details = document.querySelector("#project-details");
      const title = document.querySelector("#project-details-title");
      if (!details || !title) return;

      const titleStyle = getComputedStyle(title);
      const isHidden = titleStyle.visibility === "hidden" || parseFloat(titleStyle.opacity || "1") < 0.2;
      const routeDidNotFinish = !document.documentElement.classList.contains("is-project-details-active");
      if (!isHidden && !routeDidNotFinish) return;

      document.body.classList.add("static-project-detail-body");
      document.documentElement.classList.add("is-project-details-active", "project-native-reveal-fix");
    };

    window.setTimeout(rescueStalledNativeRoute, 1800);
    window.setTimeout(rescueStalledNativeRoute, 3200);

    window.setTimeout(() => {
      if (hasMountedProjectDetails(slug)) return;
      if (document.documentElement.classList.contains("is-project-details-active")) return;
      if (location.pathname.replace(/^\/|\/$/g, "") !== `projects/${slug}`) return;
      mountProjectRouteFallback(slug, href).catch(() => {});
    }, 3600);
  };

  let pendingProjectClick = null;

  document.addEventListener("click", (event) => {
    const card = event.target.closest(".project-item");
    if (!card) return;
    const data = dataFromCard(card);

    if (isTemplateReady()) {
      event.preventDefault();
      event.stopImmediatePropagation();
      buildProjectExperience(data, card).catch(() => {
        window.location.href = data.href;
      });
      return;
    }

    event.preventDefault();
    event.stopImmediatePropagation();

    pendingProjectClick = card;
    const replayWhenReady = () => {
      if (!pendingProjectClick) return;
      if (!isTemplateReady()) {
        window.setTimeout(replayWhenReady, 80);
        return;
      }

      const readyCard = pendingProjectClick;
      pendingProjectClick = null;
      requestAnimationFrame(() => {
        watchProjectRoute(data.slug, data.href);
        readyCard.click();
      });
    };

    replayWhenReady();
  }, true);

  document.addEventListener("click", (event) => {
    const backButton = event.target.closest("#header-center-project-back-btn");
    if (!backButton || (
      !document.documentElement.classList.contains("project-route-fallback-active") &&
      !document.documentElement.classList.contains("project-experience-open") &&
      !document.body.classList.contains("static-project-detail-body")
    )) return;
    event.preventDefault();
    event.stopImmediatePropagation();
    if (document.documentElement.classList.contains("project-experience-open")) {
      closeProjectExperience();
      return;
    }
    window.location.href = location.pathname.includes("/gallery/") ? "/gallery" : "/";
  }, true);

  window.addEventListener("popstate", () => {
    const path = location.pathname.replace(/^\/|\/$/g, "");
    if (!path.startsWith("projects/") && !/^gallery\/[^/]+\/experience/.test(path)) closeProjectExperience();
  });

  window.addEventListener("wheel", routeWheelToProjectExperience, { capture: true, passive: false });
  window.addEventListener("wheel", routeWheelToStaticProject, { capture: true, passive: false });
  window.addEventListener("scroll", syncStaticProjectRail, { passive: true });
  window.addEventListener("resize", syncStaticProjectRail);

  document.addEventListener("click", (event) => {
    if (!event.target.closest("#home-reel-video-watch-btn")) return;
    event.preventDefault();
    event.stopImmediatePropagation();
    openLocalReel();
  }, true);

  syncTemplateReadyClass();
  enableHomeSmoothScrollFallback();
  if (document.body.classList.contains("static-project-detail-body")) {
    const slug = location.pathname.match(/\/projects\/([^/?#]+)/)?.[1];
    document.documentElement.style.overflow = "auto";
    document.documentElement.style.height = "auto";
    document.body.style.overflow = "auto";
    document.body.style.height = "auto";
    if (slug) layoutStaticProjectMedia(document, `/assets/projects/${slug}/home.webp`);
    enableProjectPointerEffects(document);
    requestAnimationFrame(syncStaticProjectRail);
  }

  document.addEventListener("click", (event) => {
    const card = event.target.closest(".project-card");
    if (!card) return;
    const data = dataFromCard(card);
    if (!data.slug) return;
    event.preventDefault();
    event.stopImmediatePropagation();
    openOverlay(data);
  }, true);
})();
